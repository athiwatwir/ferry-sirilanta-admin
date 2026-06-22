<?php

namespace App\Http\Controllers;

use App\Helpers\UtilHelper;
use App\Models\Agent;
use App\Models\Booking;
use App\Models\SalesPartner;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\RouteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $agentId = env('AGENT_ID');
        //$conditionStr = 'b.agent_id = "' . $agentId . '"';

        //Default with last 7 days
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();


        $station_from = request()->station_from;
        $station_to = request()->station_to;

        $ticketno = request()->ticketno;
        $bookingno = request()->bookingno;

        $date_type = request()->date_type;
        $daterange = request()->daterange;

        $status = request()->status;
        $searchText = request()->search_text;

        $paymentno = request()->paymentno;
        $customername = request()->customername;
        $email = request()->email;
        $bookChannel = request()->book_channel;
        $tripType = request()->trip_type;

        $agent_id = request()->agent_id;
        $salesPartner = null;

        //dd($request->filled('status'));


        $query = DB::table('bookings as b')
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
                'b.isearned'
            );

        $query->where('b.agent_id', $agentId);

        if (Auth::user()->role != 'ADMIN') {
            //dd(Auth::user());
            $salesPartnerId = Auth::user()->sales_partner_id;

            $query->where('b.sales_partner_id', $salesPartnerId);

            $salesPartner = SalesPartner::with('agentAccount')->find($salesPartnerId);
        }


        $bookings = null;
        // 🔹 ถ้ามี searchText → ค้นหาทุกช่อง
        if ($request->filled('search_text')) {
            $text = $request->search_text;
            $query->where(function ($q) use ($text) {
                $q->where('br.ticketno', 'like', "%$text%")
                    ->orWhere('b.bookingno', 'like', "%$text%")
                    ->orWhere('c.fullname', 'like', "%$text%")
                    ->orWhere('c.email', 'like', "%$text%");
            });
            $bookings = $query->orderBy('b.created_at', 'DESC')->get();
        } else {
            // 🔹 เงื่อนไขอื่น ๆ เฉพาะฟิลด์
            if ($request->filled('station_from')) {
                $query->where('sf.id', $request->station_from);
            }
            if ($request->filled('station_to')) {
                $query->where('st.id', $request->station_to);
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
            if ($request->filled('book_channel')) {
                $query->where('b.book_channel', $request->book_channel);
            }
            if ($request->filled('trip_type')) {
                $query->where('b.trip_type', $request->trip_type);
            }
            if ($request->filled('aff_id')) {
                $query->where('b.aff_id', $request->aff_id);
            }
            if ($request->filled('status')) {
                $query->where('b.status', $request->status);
            } else {
                $query->whereNotIn('b.status', ['delete', 'void', 'VO', 'EXPIRED']);
            }



            if (!empty($daterange)) {
                $daterangeConvert = UtilHelper::parseDateRange($daterange);

                // แปลงเป็น Carbon object และกำหนดเวลาให้ชัดเจน
                $startDate = Carbon::parse($daterangeConvert[0])->startOfDay();
                $endDate = Carbon::parse($daterangeConvert[1])->endOfDay();
            }

            // ใช้ date column ตามเงื่อนไข
            if (!empty($daterange) && $date_type == 'booking_date') {
                $query->whereBetween('b.created_at', [$startDate, $endDate]);
                $orderColumn = 'b.created_at';
            } elseif (!empty($daterange)) {
                // สำหรับ traveldate ที่เป็น DATE type ไม่ต้องใช้ startOfDay/endOfDay
                $query->whereBetween('br.traveldate', [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d')
                ]);
                $orderColumn = 'br.traveldate';
            } else {
                $query->whereBetween('b.created_at', [$startDate, $endDate]);
                $orderColumn = 'b.created_at';
            }

            $bookings = $query->orderBy($orderColumn, 'DESC')->get();
        }


        $bookings = json_decode(json_encode($bookings), true);

        $sections = [];
        $tripTypes = $this->bookingService->getTripType();
        $bookChannels  = $this->bookingService->getBookingChannel();
        $bookingStatus = $this->bookingService->status();

        $agents = Agent::where('type', 'API')->get();

        $role = strtolower(Auth::user()->role ?? 'admin');

        if ($request->input('export') === 'excel') {
            return $this->exportBookingsExcel($bookings, $role);
        }

        if ($request->input('ispdf') === 'Y') {
            return $this->exportBookingsPdf($bookings, $role, $daterange, $startDate, $endDate, $date_type);
        }

        $employeeDashboard = null;
        if (Auth::user()->role === 'employee') {
            $userId = Auth::id();
            $salesPartnerId = Auth::user()->sales_partner_id;

            $salesBase = Booking::where('user_id', $userId)
                ->where('sales_partner_id', $salesPartnerId)
                ->whereNotIn('status', ['delete', 'void', 'VO', 'EXPIRED'])
                ->whereBetween('created_at', [$startDate, $endDate]);

            $employeeDashboard = [
                'ticket_sales_count' => (clone $salesBase)->count(),
                'ticket_sales_amount' => (float) (clone $salesBase)->sum('totalamt'),
                'pending_point' => (int) Booking::where('user_id', $userId)
                    ->where('sales_partner_id', $salesPartnerId)
                    ->where('isearned', 'N')
                    ->whereNotIn('status', ['delete', 'void', 'VO', 'EXPIRED'])
                    ->selectRaw('COALESCE(SUM(adult_passenger + child_passenger + infant_passenger), 0) as total')
                    ->value('total'),
            ];
        }

        //dd($bookings);
        return view('pages.booking.index', [
            'title' => 'Booking Management',
            'bookings' => $bookings,
            'sections' => $sections,
            'station_from' => $station_from,
            'station_to' => $station_to,
            'bookingno' => $bookingno,
            'ticketno' => $ticketno,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'bookingStatus' => $bookingStatus,
            'tripTypes' => $tripTypes,
            'bookChannels' => $bookChannels,
            'customername' => $customername,
            'paymentno' => $paymentno,
            'email' => $email,
            'bookChannel' => $bookChannel,
            'tripType' => $tripType,
            'agents' => $agents,
            'agent_id' => $agent_id,
            'searchText' => $searchText,
            'todayDate' => Carbon::now()->format('Y-m-d'),
            'tmrDate' => Carbon::now()->addDay()->format('Y-m-d'),
            'date_type' => $date_type,
            'salesPartner' => $salesPartner,
            'employeeDashboard' => $employeeDashboard,
        ]);
    }

    public function flight()
    {
        $depart_station_id = request()->depart_station_id;
        $dest_station_id = request()->dest_station_id;
        $travel_date = request('travel_date');
        $subRoutes = [];

        if (!empty($depart_station_id)) {
            $subRoutes = app(RouteService::class)->getRoutes($depart_station_id, $dest_station_id);
            //dd($subRoutes);
        }

        return view('pages.booking.flight', [
            'title' => 'Select Route',
            'depart_station_id' => $depart_station_id,
            'dest_station_id' => $dest_station_id,
            'subRoutes' => $subRoutes,
            'travel_date' => $travel_date
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sub_route_id = request('sub_route_id');
        $travel_date = request('travel_date');

        $subRoute = app(RouteService::class)->getRoute($sub_route_id);

        return view('pages.booking.create', [
            'title' => 'Create Booking',
            'sub_route_id' => $sub_route_id,
            'travel_date' => $travel_date,
            'subRoute' => $subRoute
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'departdate' => UtilHelper::dmYToYmd($request->departdate),
            'adult_passenger' => $request->adult_passenger,
            'child_passenger' => $request->child_passenger,
            'infant_passenger' => $request->infant_passenger,
            'discount' => 0,
            'trip_type' => 'O',
            'user_id' => Auth::user()->id,
            'sub_agent_id' => Auth::user()->agent_id,
            'customers' => [
                [
                    'fullname' => $request->fullname ?? '-',
                    'type' => 'ADULT',
                    'email' => '',
                    'mobile' => $request->mobile ?? '-',
                    'isdefault' => 'Y',
                    'passportno' => $request->passportno ?? '-',
                ]
            ],
            'routes' => [
                [
                    'id' => $request->sub_route_id,
                    'traveldate' => UtilHelper::dmYToYmd($request->departdate),
                    'price' => $request->price,
                    'child_price' => 0,
                    'infant_price' => 0,
                ]
            ]
        ];

        //dd($data);

        $result = app(BookingService::class)->saveDraft($data);

        return redirect()->route('booking.payment', ['invoiceno' => $result['invoiceno']]);
    }

    public function payment($invoiceno)
    {
        $booking = Booking::where('bookingno', $invoiceno)->first();

        return view('pages.booking.payment', [

            'booking' => $booking
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = Booking::whereId($id)->with(['agent', 'bookingSubRoutes', 'bookingCustomers'])->first();

        return view('pages.booking.show', [
            'title' => '',
            'booking' => $booking,
            'breadcrumbs' => [
                'Booking Management' => route('booking.index'),
                'Booking Details' => ''
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function exportBookingsExcel(array $bookings, string $role): StreamedResponse
    {
        $export = $this->prepareBookingExport($bookings, $role);
        $filename = 'booking-report-' . now()->format('Ymd_His') . '.csv';

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

    private function exportBookingsPdf(
        array $bookings,
        string $role,
        ?string $daterange,
        Carbon $startDate,
        Carbon $endDate,
        ?string $dateType
    ) {
        $export = $this->prepareBookingExport($bookings, $role);

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

        return $pdf->stream('booking-report-' . now()->format('Ymd_His') . '.pdf');
    }

    private function prepareBookingExport(array $bookings, string $role): array
    {
        $statusMap = $this->bookingService->status();
        $tripTypes = $this->bookingService->getTripType();

        $headers = match ($role) {
            'agent' => [
                'Booking Date', 'Travel Date', 'Invoice No', 'Ticket No', 'Type', 'Customer', 'Pax',
                'Price', 'Discount', 'Nett Price', 'Route', 'Departure', 'Arrival', 'Status', 'Agent Ref.',
            ],
            'broker' => [
                'Booking Date', 'Travel Date', 'Invoice No', 'Ticket No', 'Type', 'Customer', 'Pax',
                'Use Credit', 'Route', 'Departure', 'Arrival', 'Status',
            ],
            'employee' => [
                'Booking Date', 'Travel Date', 'Invoice No', 'Ticket No', 'Type', 'Customer', 'Pax',
                'Price', 'Processing Fee', 'Total Price', 'Route', 'Departure', 'Arrival', 'Status',
                'Point', 'Point Status',
            ],
            default => [
                'Booking Date', 'Travel Date', 'Invoice No', 'Ticket No', 'Type', 'Customer', 'Pax',
                'Price', 'Processing Fee', 'Total Price', 'Route', 'Departure', 'Arrival', 'Status', 'Agent Ref.',
            ],
        };

        $rows = [];
        foreach ($bookings as $booking) {
            $base = [
                $this->formatExportDateTime($booking['created_at'] ?? null),
                $this->formatExportDate($booking['traveldate'] ?? null),
                $booking['bookingno'] ?? '',
                $booking['ticketno'] ?? '',
                $tripTypes[$booking['trip_type'] ?? ''] ?? ($booking['trip_type'] ?? ''),
                $booking['customer_name'] ?? '',
                $booking['total_passenger'] ?? 0,
            ];

            [$departTime, $arrivalTime] = $this->formatExportTimes($booking);
            $status = $statusMap[$booking['status'] ?? '']['title'] ?? ($booking['status'] ?? '');
            $route = $booking['route'] ?? '';

            $rows[] = match ($role) {
                'agent' => array_merge($base, [
                    $this->formatExportPrice($booking['totalamt'] ?? 0),
                    $this->formatExportPrice($booking['payment_discount'] ?? 0),
                    $this->formatExportPrice($booking['payment_totalamt'] ?? 0),
                    $route,
                    $departTime,
                    $arrivalTime,
                    $status,
                    $booking['referenceno'] ?? '',
                ]),
                'broker' => array_merge($base, [
                    $this->formatExportPrice($booking['payment_totalamt'] ?? 0),
                    $route,
                    $departTime,
                    $arrivalTime,
                    $status,
                ]),
                'employee' => array_merge($base, [
                    $this->formatExportPrice($booking['totalamt'] ?? 0),
                    $this->formatExportPrice($booking['feeamt'] ?? 0),
                    $this->formatExportPrice($booking['payment_totalamt'] ?? 0),
                    $route,
                    $departTime,
                    $arrivalTime,
                    $status,
                    ...$this->formatEmployeePoint($booking),
                ]),
                default => array_merge($base, [
                    $this->formatExportPrice($booking['totalamt'] ?? 0),
                    $this->formatExportPrice($booking['feeamt'] ?? 0),
                    $this->formatExportPrice($booking['payment_totalamt'] ?? 0),
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

    private function formatExportDateTime(?string $value): string
    {
        return $value ? Carbon::parse($value)->format('d/m/Y H:i') : '';
    }

    private function formatExportDate(?string $value): string
    {
        return $value ? Carbon::parse($value)->format('d/m/Y') : '';
    }

    private function formatExportPrice($value): string
    {
        return number_format((float) ($value ?? 0), 2, '.', '');
    }

    private function formatExportTimes(array $booking): array
    {
        $depart = !empty($booking['depart_time'])
            ? Carbon::parse($booking['depart_time'])->format('H:i')
            : '';
        $arrival = !empty($booking['arrival_time'])
            ? Carbon::parse($booking['arrival_time'])->format('H:i')
            : '';

        return [$depart, $arrival];
    }

    private function formatEmployeePoint(array $booking): array
    {
        if (($booking['ispayment'] ?? 'N') !== 'Y') {
            return ['0', 'รอชำระเงิน'];
        }

        $point = (string) ($booking['total_passenger'] ?? 0);
        $status = ($booking['isearned'] ?? 'N') === 'Y' ? 'ถอนแล้ว' : 'ยังไม่ถอน';

        return [$point, $status];
    }
}
