<?php

namespace App\Services;

use App\Helpers\UtilHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Shared booking list (search + export) for Agent / Broker / Employee show pages.
 */
class PartnerBookingListService
{
    public function __construct(
        private BookingService $bookingService,
        private EmployeePointService $employeePoints
    ) {}

    /**
     * @return array{
     *     bookings: array,
     *     startDate: Carbon,
     *     endDate: Carbon,
     *     filters: array<string, mixed>
     * }
     */
    public function search(Request $request, string $salesPartnerId): array
    {
        $agentId = env('AGENT_ID');
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        $dateType = $request->input('date_type', 'booking_date');
        $daterange = $request->input('daterange');

        $query = $this->baseQuery()
            //->where('b.agent_id', $agentId)
            ->where('b.sales_partner_id', $salesPartnerId);

        if ($request->filled('search_text')) {
            $text = $request->search_text;
            $query->where(function ($q) use ($text) {
                $q->where('br.ticketno', 'like', "%{$text}%")
                    ->orWhere('b.bookingno', 'like', "%{$text}%")
                    ->orWhere('c.fullname', 'like', "%{$text}%")
                    ->orWhere('c.email', 'like', "%{$text}%");
            });
            $bookings = $query->orderBy('b.created_at', 'DESC')->get();
        } else {
            $stationFrom = $request->input('station_from') ?: $request->input('depart_station_id');
            $stationTo = $request->input('station_to') ?: $request->input('dest_station_id');

            if (!empty($stationFrom)) {
                $query->where('sf.id', $stationFrom);
            }
            if (!empty($stationTo)) {
                $query->where('st.id', $stationTo);
            }
            if ($request->filled('ticketno')) {
                $query->where('br.ticketno', 'like', '%' . $request->ticketno . '%');
            }
            if ($request->filled('bookingno')) {
                $query->where('b.bookingno', 'like', '%' . $request->bookingno . '%');
            }
            if ($request->filled('customername')) {
                $query->where('c.fullname', 'like', '%' . $request->customername . '%');
            }
            if ($request->filled('email')) {
                $query->where('c.email', $request->email);
            }
            if ($request->filled('trip_type')) {
                $query->where('b.trip_type', $request->trip_type);
            }
            if ($request->filled('status')) {
                $query->where('b.status', $request->status);
            } else {
                $query->whereNotIn('b.status', ['delete', 'void', 'VO', 'EXPIRED']);
            }

            if (!empty($daterange)) {
                $daterangeConvert = UtilHelper::parseDateRange($daterange);
                $startDate = Carbon::parse($daterangeConvert[0])->startOfDay();
                $endDate = Carbon::parse($daterangeConvert[1])->endOfDay();
            }

            if (!empty($daterange) && $dateType === 'booking_date') {
                $query->whereBetween('b.created_at', [$startDate, $endDate]);
                $orderColumn = 'b.created_at';
            } elseif (!empty($daterange)) {
                $query->whereBetween('br.traveldate', [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d'),
                ]);
                $orderColumn = 'br.traveldate';
            } else {
                $query->whereBetween('b.created_at', [$startDate, $endDate]);
                $orderColumn = 'b.created_at';
            }

            $bookings = $query->orderBy($orderColumn, 'DESC')->get();
        }

        $bookings = json_decode(json_encode($bookings), true) ?: [];
        $bookings = array_map(function (array $booking) {
            $booking['point'] = $this->employeePoints->forBooking($booking);
            return $booking;
        }, $bookings);

        return [
            'bookings' => $bookings,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'filters' => [
                'ticketno' => $request->input('ticketno'),
                'bookingno' => $request->input('bookingno'),
                'customername' => $request->input('customername'),
                'email' => $request->input('email'),
                'tripType' => $request->input('trip_type'),
                'status' => $request->input('status'),
                'searchText' => $request->input('search_text'),
                'date_type' => $request->input('date_type', 'booking_date'),
                'daterange' => $request->input('daterange'),
                'depart_station_id' => $request->input('depart_station_id'),
                'dest_station_id' => $request->input('dest_station_id'),
                'tripTypes' => $this->bookingService->getTripType(),
                'bookingStatus' => $this->bookingService->status(),
            ],
        ];
    }

    public function exportExcel(array $bookings, string $role, string $filenamePrefix = 'booking-report'): StreamedResponse
    {
        $export = $this->prepareExport($bookings, $role);
        $filename = $filenamePrefix . '-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($export) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, $export['headers']);
            foreach ($export['rows'] as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportPdf(
        array $bookings,
        string $role,
        ?string $daterange,
        Carbon $startDate,
        Carbon $endDate,
        ?string $dateType,
        string $filenamePrefix = 'booking-report'
    ) {
        $export = $this->prepareExport($bookings, $role);

        $pdf = Pdf::loadView('pages.booking.pdf.booking', [
            'headers' => $export['headers'],
            'rows' => $export['rows'],
            'daterange' => $daterange,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'date_type' => $dateType === 'booking_date' ? 'Booking Date' : 'Travel Date',
        ])
            ->setOption(['dpi' => 150])
            ->setPaper('A4', 'landscape');

        return $pdf->stream($filenamePrefix . '-' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * @return array{headers: array<int, string>, rows: array<int, array<int, mixed>>}
     */
    public function prepareExport(array $bookings, string $role): array
    {
        $statusMap = $this->bookingService->status();
        $tripTypes = $this->bookingService->getTripType();
        $role = strtolower($role);

        $headers = match ($role) {
            'agent' => [
                'Booking Date',
                'Travel Date',
                'Invoice No',
                'Ticket No',
                'Type',
                'Customer',
                'Pax',
                'Price',
                'Discount',
                'Nett Price',
                'Route',
                'Departure',
                'Arrival',
                'Status',
                'Agent Ref.',
            ],
            'broker' => [
                'Booking Date',
                'Travel Date',
                'Invoice No',
                'Ticket No',
                'Type',
                'Customer',
                'Pax',
                'Use Credit',
                'Route',
                'Departure',
                'Arrival',
                'Status',
            ],
            'employee' => [
                'Booking Date',
                'Travel Date',
                'Invoice No',
                'Ticket No',
                'Type',
                'Customer',
                'Pax',
                'Price',
                'Processing Fee',
                'Total Price',
                'Route',
                'Departure',
                'Arrival',
                'Status',
                'Point',
                'Point Status',
            ],
            default => [
                'Booking Date',
                'Travel Date',
                'Invoice No',
                'Ticket No',
                'Type',
                'Customer',
                'Pax',
                'Price',
                'Processing Fee',
                'Total Price',
                'Route',
                'Departure',
                'Arrival',
                'Status',
                'Agent Ref.',
            ],
        };

        $rows = [];
        foreach ($bookings as $booking) {
            $base = [
                $this->formatDateTime($booking['created_at'] ?? null),
                $this->formatDate($booking['traveldate'] ?? null),
                $booking['bookingno'] ?? '',
                $booking['ticketno'] ?? '',
                $tripTypes[$booking['trip_type'] ?? ''] ?? ($booking['trip_type'] ?? ''),
                $booking['customer_name'] ?? '',
                $booking['total_passenger'] ?? 0,
            ];

            [$departTime, $arrivalTime] = $this->formatTimes($booking);
            $status = $statusMap[$booking['status'] ?? '']['title'] ?? ($booking['status'] ?? '');
            $route = $booking['route'] ?? '';

            $rows[] = match ($role) {
                'agent' => array_merge($base, [
                    $this->formatPrice($booking['totalamt'] ?? 0),
                    $this->formatPrice($booking['payment_discount'] ?? 0),
                    $this->formatPrice($booking['payment_totalamt'] ?? 0),
                    $route,
                    $departTime,
                    $arrivalTime,
                    $status,
                    $booking['referenceno'] ?? '',
                ]),
                'broker' => array_merge($base, [
                    $this->formatPrice($booking['payment_totalamt'] ?? 0),
                    $route,
                    $departTime,
                    $arrivalTime,
                    $status,
                ]),
                'employee' => array_merge($base, [
                    $this->formatPrice($booking['totalamt'] ?? 0),
                    $this->formatPrice($booking['feeamt'] ?? 0),
                    $this->formatPrice($booking['payment_totalamt'] ?? 0),
                    $route,
                    $departTime,
                    $arrivalTime,
                    $status,
                    ...$this->formatEmployeePoint($booking),
                ]),
                default => array_merge($base, [
                    $this->formatPrice($booking['totalamt'] ?? 0),
                    $this->formatPrice($booking['feeamt'] ?? 0),
                    $this->formatPrice($booking['payment_totalamt'] ?? 0),
                    $route,
                    $departTime,
                    $arrivalTime,
                    $status,
                    $booking['referenceno'] ?? '',
                ]),
            };
        }

        return compact('headers', 'rows');
    }

    private function baseQuery()
    {
        return DB::table('bookings as b')
            ->join('booking_sub_routes as br', 'b.id', '=', 'br.booking_id')
            ->join('sub_routes as sr', 'br.sub_route_id', '=', 'sr.id')
            ->leftJoin('routes as r', 'sr.route_id', '=', 'r.id')
            ->join('stations as sf', 'r.depart_station_id', '=', 'sf.id')
            ->join('stations as st', 'r.dest_station_id', '=', 'st.id')
            ->join('booking_customers as bc', function ($join) {
                $join->on('b.id', '=', 'bc.booking_id')
                    ->where('bc.isdefault', '=', 'Y');
            })
            ->join('customers as c', 'bc.customer_id', '=', 'c.id')
            ->leftJoin('agents as ag', 'b.aff_id', '=', 'ag.id')
            ->leftJoin('payments as p', 'b.id', '=', 'p.booking_id')
            ->leftJoin('users as u', 'b.user_id', '=', 'u.id')
            ->select(
                'b.id',
                'b.created_at',
                'b.bookingno',
                'br.ticketno',
                DB::raw('(b.adult_passenger+b.child_passenger+b.infant_passenger) as total_passenger'),
                'b.adult_passenger',
                'b.child_passenger',
                'b.infant_passenger',
                'b.trip_type',
                'br.type',
                'b.amend',
                DB::raw('concat(sf.nickname,"-",st.nickname) as route'),
                'br.traveldate',
                'b.ispayment',
                'b.book_channel',
                'c.fullname as customer_name',
                'c.email',
                'sr.depart_time',
                'sr.arrival_time',
                'b.totalamt',
                'b.subtotal',
                'b.nettamt',
                'p.feeamt',
                'p.discount as payment_discount',
                'p.totalamt as payment_totalamt',
                'p.ispaid',
                'b.status',
                'b.ispremiumflex',
                'b.isemailsent',
                'b.referenceno',
                'ag.name as agent_name',
                'ag.code as agent_code',
                'b.agent_id',
                'ag.logo as agent_logo',
                'b.isearned',
                DB::raw('(select count(*) from booking_sub_routes bsr_cnt where bsr_cnt.booking_id = b.id) as sub_route_count'),
                'u.name as user_name'
            );
    }

    private function formatDateTime(?string $value): string
    {
        return $value ? Carbon::parse($value)->format('d/m/Y H:i') : '';
    }

    private function formatDate(?string $value): string
    {
        return $value ? Carbon::parse($value)->format('d/m/Y') : '';
    }

    private function formatPrice($value): string
    {
        return number_format((float) ($value ?? 0), 2, '.', '');
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function formatTimes(array $booking): array
    {
        $depart = !empty($booking['depart_time'])
            ? Carbon::parse($booking['depart_time'])->format('H:i')
            : '';
        $arrival = !empty($booking['arrival_time'])
            ? Carbon::parse($booking['arrival_time'])->format('H:i')
            : '';

        return [$depart, $arrival];
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function formatEmployeePoint(array $booking): array
    {
        if (($booking['ispayment'] ?? 'N') !== 'Y') {
            return ['0', 'รอชำระเงิน'];
        }

        $point = (string) $this->employeePoints->forBooking($booking);
        $status = ($booking['isearned'] ?? 'N') === 'Y' ? 'ถอนแล้ว' : 'ยังไม่ถอน';

        return [$point, $status];
    }
}
