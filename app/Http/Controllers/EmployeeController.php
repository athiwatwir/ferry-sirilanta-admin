<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\SalesPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brokers = SalesPartner::with('brokerPoint', 'user')->where('type', 'employee')->where('agent_id', env('AGENT_ID'))->get();

        $points = Booking::whereIn('sales_partner_id', $brokers->pluck('id'))
            ->where('isearned', 'N')
            ->whereNotIn('status', ['delete', 'void', 'VO', 'EXPIRED'])
            ->selectRaw('sales_partner_id, sum(adult_passenger + child_passenger + infant_passenger) as point')
            ->groupBy('sales_partner_id')
            ->pluck('point', 'sales_partner_id');

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
    public function show(string $id)
    {
        $employee = SalesPartner::with('brokerPoint', 'user')->find($id);

        $bookings = Booking::where('sales_partner_id', $id)
            ->where('isearned', 'N')
            ->whereNotIn('status', ['delete', 'void', 'VO', 'EXPIRED'])
            ->orderBy('departdate', 'desc')
            ->get()
            ->map(function ($booking) {
                $booking->point = $booking->adult_passenger + $booking->child_passenger + $booking->infant_passenger;
                return $booking;
            });

        $totalPoint = $bookings->sum('point');
        $employee->point = $totalPoint;

        return view('pages.employee.show', [
            'title' => 'Employee > ' . $employee->name,
            'breadcrumbs' => [
                'All Employee' => route('employee.index'),
                'View' => ''
            ],
            'employee' => $employee,
            'bookings' => $bookings,
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


    public function point()
    {
        $userId = Auth::user()->id;
        $salesPartnerId = Auth::user()->sales_partner_id;

        if (!$userId) {
            return view('pages.employee.point', [
                'title' => 'Your Point',
                'totalPoint' => 0,
                'bookings' => collect(),
            ]);
        }

        $bookings = Booking::where('user_id', $userId)
            ->where('sales_partner_id', $salesPartnerId)
            ->where('isearned', 'N')
            ->whereNotIn('status', ['delete', 'void', 'VO', 'EXPIRED'])
            ->orderBy('departdate', 'desc')
            ->get()
            ->map(function ($booking) {
                $booking->point = $booking->adult_passenger + $booking->child_passenger + $booking->infant_passenger;
                return $booking;
            });

        $totalPoint = $bookings->sum('point');

        return view('pages.employee.point', [
            'title' => 'Your Point',
            'totalPoint' => $totalPoint,
            'bookings' => $bookings,
        ]);
    }

    /**
     * รายการจองที่ยังไม่ถอน point (isearned = N) สำหรับ modal ถอน point
     */
    public function earnPointBookings(string $id)
    {
        $bookings = Booking::where('sales_partner_id', $id)
            ->where('isearned', 'N')
            ->whereNotIn('status', ['delete', 'void', 'VO', 'EXPIRED'])
            ->orderBy('departdate', 'desc')
            ->get()
            ->map(function ($booking) {
                $booking->point = $booking->adult_passenger + $booking->child_passenger + $booking->infant_passenger;
                return $booking;
            });

        return response()->json([
            'bookings' => $bookings->map(fn($b) => [
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
     * อัพเดท isearned = Y สำหรับรายการที่เลือก (ถอน point)
     */
    public function earnPoint(Request $request, string $id)
    {
        $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'uuid',
        ]);

        $updated = Booking::where('sales_partner_id', $id)
            ->where('isearned', 'N')
            ->whereIn('id', $request->booking_ids)
            ->update(['isearned' => 'Y']);

        return response()->json([
            'success' => true,
            'message' => 'ถอน point สำเร็จ ' . $updated . ' รายการ',
            'updated_count' => $updated,
        ]);
    }
}
