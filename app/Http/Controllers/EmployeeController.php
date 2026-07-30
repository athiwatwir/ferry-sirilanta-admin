<?php

namespace App\Http\Controllers;

use App\Helpers\UtilHelper;
use App\Models\AgentAccount;
use App\Models\AgentAccountTransection;
use App\Models\Booking;
use App\Models\SalesPartner;
use App\Services\EmployeePointService;
use App\Services\PartnerBookingListService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeController extends Controller
{
    public function __construct(
        private PartnerBookingListService $partnerBookings,
        private EmployeePointService $employeePoints
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brokers = SalesPartner::with('brokerPoint', 'user')->where('type', 'employee')->where('agent_id', env('AGENT_ID'))->get();

        $points = $this->employeePoints->pendingTotalsByPartnerIds($brokers->pluck('id'));

        foreach ($brokers as $broker) {
            $broker->point = (int) ($points[$broker->id] ?? 0);
        }

        return view('pages.employee.index', [
            'title' => 'Employee',
            'brokers' => $brokers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('pages.employee.create', [
            'title' => 'Create Employee',
            'breadcrumbs' => [
                'All Employee' => route('employee.index'),
                'Create' => ''
            ],
            'type' => 'employee'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $employee = SalesPartner::with('brokerPoint', 'user', 'agentAccount')->findOrFail($id);

        if ($employee->type !== 'employee') {
            abort(404);
        }

        // ดึง transactions จาก AgentAccount
        $transactions = collect();
        if ($employee->agentAccount) {
            $transactions = AgentAccountTransection::where('agent_account_id', $employee->agentAccount->id)
                ->where('type', 'withdraw')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $employee->point = $this->employeePoints->pendingTotalForPartner($id);

        $bookingResult = $this->partnerBookings->search($request, $employee->id);
        $bookings = $bookingResult['bookings'];

        if ($request->input('export') === 'excel') {
            return $this->partnerBookings->exportExcel($bookings, 'employee', 'employee-booking-report');
        }

        if ($request->input('ispdf') === 'Y') {
            return $this->partnerBookings->exportPdf(
                $bookings,
                'employee',
                $request->input('daterange'),
                $bookingResult['startDate'],
                $bookingResult['endDate'],
                $request->input('date_type'),
                'employee-booking-report'
            );
        }

        return view('pages.employee.show', array_merge($bookingResult['filters'], [
            'title' => 'Employee > ' . $employee->name,
            'breadcrumbs' => [
                'All Employee' => route('employee.index'),
                'View' => '',
            ],
            'employee' => $employee,
            'transactions' => $transactions,
            'bookings' => $bookings,
            'startDate' => $bookingResult['startDate'],
            'endDate' => $bookingResult['endDate'],
        ]));
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
        $employee = SalesPartner::with('user')->findOrFail($id);

        if ($employee->type !== 'employee') {
            abort(404);
        }

        $userId = $employee->user?->id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'size:8',
                'regex:/^[A-Za-z0-9]{8}$/',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => 'nullable|string|min:8',
        ], [
            'name.required' => 'กรุณาระบุชื่อ',
            'code.required' => 'กรุณาระบุรหัส Code',
            'code.size' => 'Code ต้องมีจำนวน 8 ตัวอักษรเท่านั้น',
            'code.regex' => 'Code ต้องเป็นภาษาอังกฤษหรือตัวเลขเท่านั้น (8 ตัว)',
            'email.required' => 'กรุณาระบุอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว กรุณาใช้อีเมลอื่น',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
        ]);

        $employee->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
        ]);

        if ($employee->user) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'code' => $validated['code'],
            ];
            if (!empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }
            $employee->user->update($userData);
        }

        return redirect()->route('employee.show', $employee)->with('success', 'อัปเดตข้อมูล Employee เรียบร้อย');
    }

    public function changeStatus(string $id)
    {
        $employee = SalesPartner::findOrFail($id);

        if ($employee->type !== 'employee') {
            abort(404);
        }

        $employee->isactive = $employee->isactive === 'Y' ? 'N' : 'Y';
        $employee->save();

        return redirect()
            ->back()
            ->with('success', $employee->isactive === 'Y' ? 'เปิดใช้งาน Employee แล้ว' : 'ปิดใช้งาน Employee แล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function point()
    {
        $userId = Auth::user()->id;
        $salesPartnerId = Auth::user()->sales_partner_id;

        if (!$userId) {
            return view('pages.employee.point', [
                'title' => 'Your Point',
                'totalPoint' => 0,
                'bookings' => collect(),
                'transactions' => collect(),
                'tripTypes' => [
                    'O' => 'One-Way',
                    'R' => 'Return',
                    'M' => 'Multiple',
                ],
            ]);
        }

        $bookings = $this->employeePoints->attachPoint(
            $this->employeePoints->applyPendingFilters(
                Booking::where('user_id', $userId)->where('sales_partner_id', $salesPartnerId)
            )
                ->orderBy('departdate', 'desc')
                ->get()
        );

        $totalPoint = $this->employeePoints->sumPoints($bookings);

        // ดึง transactions จาก AgentAccount
        $transactions = collect();
        $salesPartner = SalesPartner::with('agentAccount')->find($salesPartnerId);
        if ($salesPartner && $salesPartner->agentAccount) {
            $transactions = AgentAccountTransection::where('agent_account_id', $salesPartner->agentAccount->id)
                ->where('type', 'withdraw')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('pages.employee.point', [
            'title' => 'Your Point',
            'totalPoint' => $totalPoint,
            'bookings' => $bookings,
            'transactions' => $transactions,
            'tripTypes' => [
                'O' => 'One-Way',
                'R' => 'Return',
                'M' => 'Multiple',
            ],
        ]);
    }

    /**
     * รายการจองที่ยังไม่ถอน point (isearned = N) สำหรับ modal ถอน point
     */
    public function earnPointBookings(string $id)
    {
        $bookings = $this->employeePoints->pendingBookingsForPartner($id);

        return response()->json([
            'bookings' => $bookings->map(fn ($b) => [
                'id' => $b->id,
                'bookingno' => $b->bookingno,
                'departdate' => $b->departdate?->format('d/m/Y'),
                'adult_passenger' => $b->adult_passenger,
                'child_passenger' => $b->child_passenger,
                'infant_passenger' => $b->infant_passenger,
                'point' => $b->point,
            ]),
        ]);
    }

    /**
     * หน้าถอน Point — กรองตามช่วงวันที่ + สรุปผล + export
     */
    public function withdrawPoint(Request $request, string $id)
    {
        $employee = SalesPartner::with('user', 'agentAccount')->findOrFail($id);
        if ($employee->type !== 'employee') {
            abort(404);
        }

        $dateType = $request->input('date_type', 'travel_date');
        $daterange = $request->input('daterange');
        $startDate = Carbon::now()->subDays(29)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        $filtered = $request->boolean('filtered') || $request->filled('daterange') || $request->filled('export') || $request->input('ispdf') === 'Y';

        if (!empty($daterange)) {
            [$start, $end] = UtilHelper::parseDateRange($daterange);
            $startDate = Carbon::parse($start)->startOfDay();
            $endDate = Carbon::parse($end)->endOfDay();
        }

        $bookings = collect();
        $summary = $this->employeePoints->summarize(collect());

        if ($filtered) {
            $bookings = $this->employeePoints->pendingBookingsInDateRange($id, $dateType, $startDate, $endDate);
            $summary = $this->employeePoints->summarize($bookings);
        }

        if ($request->input('export') === 'excel') {
            return $this->exportWithdrawPointExcel($employee, $bookings, $summary, $daterange, $startDate, $endDate, $dateType);
        }

        if ($request->input('ispdf') === 'Y') {
            return $this->exportWithdrawPointPdf($employee, $bookings, $summary, $daterange, $startDate, $endDate, $dateType);
        }

        return view('pages.employee.withdraw-point', [
            'title' => 'ถอน Point > ' . $employee->name,
            'breadcrumbs' => [
                'All Employee' => route('employee.index'),
                'View' => route('employee.show', $employee),
                'ถอน Point' => '',
            ],
            'employee' => $employee,
            'bookings' => $bookings,
            'summary' => $summary,
            'filtered' => $filtered,
            'date_type' => $dateType,
            'daterange' => $daterange,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'tripTypes' => [
                'O' => 'One-Way',
                'R' => 'Return',
                'M' => 'Multiple',
            ],
        ]);
    }

    /**
     * ยืนยันถอน Point จาก booking ที่เลือก
     */
    public function withdrawPointConfirm(Request $request, string $id)
    {
        $request->validate([
            'date_type' => 'required|in:booking_date,travel_date',
            'daterange' => 'required|string',
            'booking_ids' => 'required|array|min:1',
            'booking_ids.*' => 'uuid',
        ], [
            'daterange.required' => 'กรุณาเลือกช่วงวันที่ก่อนยืนยันถอน Point',
            'booking_ids.required' => 'กรุณาเลือก booking ที่ต้องการถอน Point',
            'booking_ids.min' => 'กรุณาเลือกอย่างน้อย 1 รายการ',
        ]);

        $employee = SalesPartner::with('agentAccount')->findOrFail($id);
        if ($employee->type !== 'employee') {
            abort(404);
        }

        [$start, $end] = UtilHelper::parseDateRange($request->daterange);
        $startDate = Carbon::parse($start)->startOfDay();
        $endDate = Carbon::parse($end)->endOfDay();

        $bookings = $this->employeePoints
            ->pendingBookingsInDateRange($id, $request->date_type, $startDate, $endDate)
            ->whereIn('id', $request->booking_ids)
            ->values();

        if ($bookings->isEmpty()) {
            return redirect()
                ->route('employee.withdrawPoint', [
                    'employee' => $id,
                    'date_type' => $request->date_type,
                    'daterange' => $request->daterange,
                    'filtered' => 1,
                ])
                ->with('warning', 'ไม่พบรายการจองที่สามารถถอน Point ได้จากรายการที่เลือก');
        }

        if (!$employee->agentAccount) {
            AgentAccount::create([
                'sales_partner_id' => $id,
                'type' => 'point',
                'credit_balance' => 0,
                'wallet_balance' => 0,
                'credit_limit' => 0,
            ]);
            $employee = SalesPartner::with('agentAccount')->findOrFail($id);
        }

        $bookingIds = $bookings->pluck('id')->all();
        $totalPoint = $this->employeePoints->sumPoints($bookings);

        $updated = Booking::where('sales_partner_id', $id)
            ->where('isearned', 'N')
            ->whereIn('id', $bookingIds)
            ->update(['isearned' => 'Y']);

        if ($totalPoint > 0 && $updated > 0) {
            $bookingNos = $bookings->pluck('bookingno')->all();
            $description = 'ถอน point จาก ' . $updated . ' รายการ: ' . implode(', ', array_slice($bookingNos, 0, 5));
            if (count($bookingNos) > 5) {
                $description .= ' และอีก ' . (count($bookingNos) - 5) . ' รายการ';
            }
            $description .= ' | ช่วง ' . $request->daterange;

            AgentAccountTransection::create([
                'agent_account_id' => $employee->agentAccount->id,
                'type' => 'withdraw',
                'amount' => $totalPoint,
                'description' => $description,
                'isapproved' => 'Y',
            ]);
        }

        return redirect()
            ->route('employee.show', $employee)
            ->with('success', "ถอน Point สำเร็จ {$updated} รายการ รวม {$totalPoint} point");
    }

    /**
     * อัพเดท isearned = Y สำหรับรายการที่เลือก (ถอน point)
     */
    public function earnPoint(Request $request, string $id)
    {

        $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'uuid',
        ]);

        // หา SalesPartner และ AgentAccount
        $salesPartner = SalesPartner::with('agentAccount')->findOrFail($id);

        if (!$salesPartner->agentAccount) {
            AgentAccount::create([
                'sales_partner_id' => $id,
                'type' => 'point',
                'credit_balance' => 0,
                'wallet_balance' => 0,
                'credit_limit' => 0,
            ]);
            $salesPartner = SalesPartner::with('agentAccount')->findOrFail($id);
        }

        // ดึง bookings ที่เลือก
        $bookings = Booking::where('sales_partner_id', $id)
            ->where('isearned', 'N')
            ->whereIn('id', $request->booking_ids)
            ->get();

        // คำนวณ point รวม
        $totalPoint = $this->employeePoints->sumPoints($bookings);

        // อัพเดท isearned = Y
        $updated = Booking::where('sales_partner_id', $id)
            ->where('isearned', 'N')
            ->whereIn('id', $request->booking_ids)
            ->update(['isearned' => 'Y']);

        // บันทึกลง AgentAccountTransection
        if ($totalPoint > 0 && $updated > 0) {
            $bookingNos = $bookings->pluck('bookingno')->toArray();
            $description = 'ถอน point จาก ' . $updated . ' รายการ: ' . implode(', ', array_slice($bookingNos, 0, 5));
            if (count($bookingNos) > 5) {
                $description .= ' และอีก ' . (count($bookingNos) - 5) . ' รายการ';
            }

            AgentAccountTransection::create([
                'agent_account_id' => $salesPartner->agentAccount->id,
                'type' => 'withdraw',
                'amount' => $totalPoint,
                'description' => $description,
                'isapproved' => 'Y', // อนุมัติอัตโนมัติสำหรับ point
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'ถอน point สำเร็จ ' . $updated . ' รายการ รวม ' . $totalPoint . ' point',
            'updated_count' => $updated,
            'total_point' => $totalPoint,
        ]);
    }

    private function exportWithdrawPointExcel(
        SalesPartner $employee,
        $bookings,
        array $summary,
        ?string $daterange,
        Carbon $startDate,
        Carbon $endDate,
        string $dateType
    ): StreamedResponse {
        $filename = 'withdraw-point-' . ($employee->code ?: $employee->id) . '-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($bookings, $summary, $daterange, $startDate, $endDate, $dateType, $employee) {
            $tripTypes = [
                'O' => 'One-Way',
                'R' => 'Return',
                'M' => 'Multiple',
            ];
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['Employee', $employee->name]);
            fputcsv($handle, ['Code', $employee->code ?? '']);
            fputcsv($handle, [
                $dateType === 'booking_date' ? 'Booking Date' : 'Travel Date',
                $daterange ?: ($startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y')),
            ]);
            fputcsv($handle, ['Total Point', $summary['total_point']]);
            fputcsv($handle, ['Total Amount', number_format($summary['total_amount'], 2, '.', '')]);
            fputcsv($handle, ['Booking Count', $summary['booking_count']]);
            fputcsv($handle, ['Passenger Count', $summary['passenger_count']]);
            fputcsv($handle, []);
            fputcsv($handle, [
                'Booking Date', 'Travel Date', 'Booking No', 'Trip Type', 'Adult', 'Point', 'Amount',
            ]);

            foreach ($bookings as $booking) {
                fputcsv($handle, [
                    $booking->created_at?->format('d/m/Y H:i') ?? '',
                    $booking->departdate?->format('d/m/Y') ?? '',
                    $booking->bookingno,
                    $tripTypes[$booking->trip_type] ?? ($booking->trip_type ?? ''),
                    $booking->adult_passenger,
                    $booking->point,
                    number_format((float) $booking->totalamt, 2, '.', ''),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function exportWithdrawPointPdf(
        SalesPartner $employee,
        $bookings,
        array $summary,
        ?string $daterange,
        Carbon $startDate,
        Carbon $endDate,
        string $dateType
    ) {
        $pdf = Pdf::loadView('pages.employee.pdf.withdraw-point', [
            'employee' => $employee,
            'bookings' => $bookings,
            'summary' => $summary,
            'daterange' => $daterange,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'date_type' => $dateType === 'booking_date' ? 'Booking Date' : 'Travel Date',
            'tripTypes' => [
                'O' => 'One-Way',
                'R' => 'Return',
                'M' => 'Multiple',
            ],
        ])
            ->setOption(['dpi' => 150])
            ->setPaper('A4', 'landscape');

        return $pdf->stream('withdraw-point-' . now()->format('Ymd_His') . '.pdf');
    }
}
