<?php

namespace App\Http\Controllers;

use App\Helpers\UtilHelper;
use App\Models\Agent;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{


    public function index()
    {


        return view('pages.report.index', [
            'title' => 'Report',

        ]);
    }

    public function booking()
    {
        $depart_station_id = request()->depart_station_id;
        $dest_station_id = request()->dest_station_id;
        $subRoutes = ['' => '-- ALL --'];

        if (!empty($depart_station_id) && !empty($dest_station_id)) {
            $route = Route::where('depart_station_id', $depart_station_id)->where('dest_station_id', $dest_station_id)->with(['subRoutes'])->first();

            if (!empty($route->subRoutes)) {
                foreach ($route->subRoutes as $item) {
                    $subRoutes[$item->id] = $item->depart_time->format('H:i') . '/' . $item->arrival_time->format('H:i');
                }
            }
        }

        $agents = Agent::where('type', 'API')->pluck('name', 'id')->toArray();

        $agents[''] = '- ALL -';


        return view('pages.report.booking', [
            'title' => 'Booking Report',
            'breadcrumbs' => [
                'Report' => route('report.index'),
                'Booking Report' => ''
            ],
            'depart_station_id' => $depart_station_id,
            'dest_station_id' => $dest_station_id,
            'subRoutes' => $subRoutes,
            'agents' => $agents
        ]);
    }

    public function showBooking(Request $request)
    {
        //dd($request->all());
        $depart_station_id = $request->depart_station_id;
        $dest_station_id = $request->dest_station_id;
        $sub_route_id = $request->sub_route_id;
        $daterange = $request->daterange;

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
        }

        if (!empty($dest_station_id)) {
            $bookings = $bookings->where('dest.id', $dest_station_id);
        }

        $bookings = $bookings->get();

        //dd($bookings);



        return view('pages.report.show_booking', [
            'title' => 'Booking Report',
            'breadcrumbs' => [
                'Report' => route('report.index'),
                'Booking Report' => route('report.booking'),
                'Booking Report Detail' => ''
            ],
            'bookings' => $bookings
        ]);
    }

    public function account()
    {

        $depart_station_id = request()->depart_station_id;
        $dest_station_id = request()->dest_station_id;
        $subRoutes = ['' => '-- ALL --'];

        if (!empty($depart_station_id) && !empty($dest_station_id)) {
            $route = Route::where('depart_station_id', $depart_station_id)->where('dest_station_id', $dest_station_id)->with(['subRoutes'])->first();

            if (!empty($route->subRoutes)) {
                foreach ($route->subRoutes as $item) {
                    $subRoutes[$item->id] = $item->depart_time->format('H:i') . '/' . $item->arrival_time->format('H:i');
                }
            }
        }

        $agents = Agent::where('type', 'API')->pluck('name', 'id')->toArray();

        $agents[''] = '- ALL -';

        return view('pages.report.account', [
            'title' => 'Account Report',
            'breadcrumbs' => [
                'Report' => route('report.index'),
                'Account Report' => ''
            ],
            'depart_station_id' => $depart_station_id,
            'dest_station_id' => $dest_station_id,
            'subRoutes' => $subRoutes,
            'agents' => $agents
        ]);
    }

    public function showAccount(Request $request)
    {

        $depart_station_id = $request->depart_station_id;
        $dest_station_id = $request->dest_station_id;
        $sub_route_id = $request->sub_route_id;
        $daterange = $request->daterange;

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
        }

        if (!empty($dest_station_id)) {
            $bookings = $bookings->where('dest.id', $dest_station_id);
        }

        $bookings = $bookings->get();

        //dd($bookings);



        return view('pages.report.show_account', [
            'title' => 'Account Report',
            'breadcrumbs' => [
                'Report' => route('report.index'),
                'Account Report' => route('report.account'),
                'Account Report Detail' => ''
            ],
            'bookings' => $bookings
        ]);
    }
}
