<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Information;
use App\Models\Station;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Helpers\UtilHelper;

class PrintController extends Controller
{
    //

    public function ticket($bookingno = null)
    {
        $booking = Booking::where('bookingno', $bookingno)->with(['agent', 'bookingSubRoutes', 'bookingCustomers'])->first();
        $term = Information::where('position', 'TERM_TICKET')->where('agent_id', env('AGENT_ID'))->first();
        $statusLabel = BookingService::status();
        $bookings[] = $booking;
        $tripTypes = app(BookingService::class)->getTripType();


        Pdf::setOption(['dpi' => 150,  'debugCss' => true]);
        $pdf = Pdf::loadView('print.ticket_v2', ['bookings' => $bookings, 'term' => $term, 'statusLabel' => $statusLabel, 'tripTypes' => $tripTypes]);


        //dd($term);
        /*
        if($booking->ispayment=='N'){
            //dd($booking);
            $pdf = Pdf::loadView('print.ticket_v2_nopayment', ['bookings' => $bookings, 'term' => $term]);
        }
            */

        return $pdf->stream();
    }


    public function detail($bookingno)
    {
        $booking = Booking::where('bookingno', $bookingno)->with(['agent', 'bookingSubRoutes', 'defaultCustomer'])->first();

        //dd($booking);
        $statusLabel = BookingService::status();

        $path = public_path('images/banner-02.jpg');
        $bannerBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($path));

        $viewName = 'print.detail';


        $pdf = Pdf::loadView($viewName, [
            'booking' => $booking,
            'statusLabel' => $statusLabel,
            'bannerBase64' => $bannerBase64
        ])
            ->setOption([
                'dpi' => 150,

            ])
            ->setPaper([0, 0, 288, 432], 'portrait'); // 4x6 นิ้ว (72 dpi)

        return $pdf->stream('detail_' . $bookingno . '.pdf');
    }


    public function reportBooking(Request $request)
    {
        $depart_station_id = $request->depart_station_id;
        $dest_station_id = $request->dest_station_id;
        $sub_route_id = $request->sub_route_id;
        $daterange = $request->daterange;

        $agentId = $request->agent_id;
        $stationFrom = 'All';
        $stationTo = 'All';
        $time = 'All';
        $agent = 'All';

        $fillDates = UtilHelper::parseDateRange($daterange);

        $bookings = DB::table('bookings as b')
            ->select([
                'b.bookingno as invoiceno',
                'depart.nickname as depart_nickname',
                'dest.nickname as dest_nickname',
                'sr.depart_time',
                'sr.arrival_time',
                'c.fullname',
                'c.email',
                'b.adult_passenger',
                'b.child_passenger',
                'b.infant_passenger',
                'a.name as agent_name',
                'b.status',
                DB::raw('CONCAT(depart.nickname, "-", dest.nickname) AS route_name'),
                'bsr.ticketno'
            ])
            ->join('booking_customers as bc', function ($join) {
                $join->on('b.id', '=', 'bc.booking_id')
                    ->where('bc.isdefault', '=', 'Y');
            })
            ->join('customers as c', 'bc.customer_id', '=', 'c.id')

            ->join('booking_sub_routes as bsr', 'b.id', '=', 'bsr.booking_id')
            ->join('sub_routes as sr', 'bsr.sub_route_id', '=', 'sr.id')
            ->join('routes as r', 'sr.route_id', '=', 'r.id')
            ->join('stations as depart', 'r.depart_station_id', '=', 'depart.id')
            ->join('stations as dest', 'r.dest_station_id', '=', 'dest.id')
            ->leftJoin('agents as a', 'b.agent_id', '=', 'a.id')

            ->whereBetween('bsr.traveldate', $fillDates);

        if (!empty($depart_station_id)) {
            $bookings = $bookings->where('depart.id', $depart_station_id);

            $station = Station::whereId($depart_station_id)->first();
            $stationFrom = $station->name_en;
        }

        if (!empty($dest_station_id)) {
            $bookings = $bookings->where('dest.id', $dest_station_id);
            $station = Station::whereId($dest_station_id)->first();
            $stationTo = $station->name_en;
        }


        $bookings = $bookings->get();

        $pdf = Pdf::loadView('print.report_booking', [
            'bookings' => $bookings,
            'daterange' => $daterange,
            'stationFrom' => $stationFrom,
            'stationTo' => $stationTo,
            'time' => $time,
            'agent' => $agent
        ])
            ->setOption([
                'dpi' => 150,
            ])
            ->setPaper('A4', 'landscape'); // ✅ ใช้ A4 แนวนอน

        return $pdf->stream('report-booking.pdf');
    }


    public function reportAccount(Request $request)
    {
        $depart_station_id = $request->depart_station_id;
        $dest_station_id = $request->dest_station_id;
        $sub_route_id = $request->sub_route_id;
        $daterange = $request->daterange;
        $agentId = $request->agent_id;
        $stationFrom = 'All';
        $stationTo = 'All';
        $time = 'All';
        $agent = 'All';

        $fillDates = UtilHelper::parseDateRange($daterange);

        $bookings = DB::table('bookings as b')
            ->select([
                'b.bookingno as invoiceno',
                'depart.nickname as depart_nickname',
                'dest.nickname as dest_nickname',
                'sr.depart_time',
                'sr.arrival_time',
                'c.fullname',
                'p.totalamt',
                'b.totalamt',
                'b.nettamt',
                'a.name as agent_name',
                'b.status',
                DB::raw('CONCAT(depart.nickname, "-", dest.nickname) AS route_name'),
                'bsr.ticketno',
                'p.docdate'
            ])
            ->join('booking_customers as bc', function ($join) {
                $join->on('b.id', '=', 'bc.booking_id')
                    ->where('bc.isdefault', '=', 'Y');
            })
            ->join('customers as c', 'bc.customer_id', '=', 'c.id')
            ->join('payments as p', 'b.id', '=', 'p.booking_id')
            ->join('booking_sub_routes as bsr', 'b.id', '=', 'bsr.booking_id')
            ->join('sub_routes as sr', 'bsr.sub_route_id', '=', 'sr.id')
            ->join('routes as r', 'sr.route_id', '=', 'r.id')
            ->join('stations as depart', 'r.depart_station_id', '=', 'depart.id')
            ->join('stations as dest', 'r.dest_station_id', '=', 'dest.id')
            ->leftJoin('agents as a', 'b.agent_id', '=', 'a.id')
            ->where('b.status', 'CO')
            ->whereBetween('p.docdate', $fillDates);

        if (!empty($depart_station_id)) {
            $bookings = $bookings->where('depart.id', $depart_station_id);

            $station = Station::whereId($depart_station_id)->first();
            $stationFrom = $station->name_en;
        }

        if (!empty($dest_station_id)) {
            $bookings = $bookings->where('dest.id', $dest_station_id);
            $station = Station::whereId($dest_station_id)->first();
            $stationTo = $station->name_en;
        }

        $bookings = $bookings->get();

        $pdf = Pdf::loadView('print.report_account', [
            'bookings' => $bookings,
            'daterange' => $daterange,
            'stationFrom' => $stationFrom,
            'stationTo' => $stationTo,
            'time' => $time,
            'agent' => $agent
        ])
            ->setOption([
                'dpi' => 150,
            ])
            ->setPaper('A4', 'landscape'); // ✅ ใช้ A4 แนวนอน

        return $pdf->stream('report-account.pdf');
    }
}
