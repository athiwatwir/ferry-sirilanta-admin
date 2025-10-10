<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $station_from = request()->station_from;
        $station_to = request()->station_to;
        $departdate = request()->departdate;
        $ticketno = request()->ticketno;
        $bookingno = request()->bookingno;
        $daterange = request()->daterange;
        $status = request()->status;
        $searchText = request()->search_text;

        $paymentno = request()->paymentno;
        $customername = request()->customername;
        $email = request()->email;
        $bookChannel = request()->book_channel;
        $tripType = request()->trip_type;

        $agent_id = request()->agent_id;


        $sql = 'select
        b.id,b.created_at,b.bookingno,b.ticketno,b.adult_passenger,b.child_passenger,b.infant_passenger,
        (b.adult_passenger+b.child_passenger+b.infant_passenger) as total_passenger,
        b.trip_type,br.type,b.amend,concat(sf.nickname,"-",st.nickname) as route,br.traveldate,b.ispayment,
        b.book_channel,c.fullname as customer_name,c.email,sr.depart_time,sr.arrival_time,b.totalamt,b.subtotal,
        b.status,b.ispremiumflex,b.isemailsent,b.referenceno,ag.name as agent_name,ag.code as agent_code
    from
        bookings b
        join booking_sub_routes br on b.id = br.booking_id
        join sub_routes sr on br.sub_route_id = sr.id
        join routes r on sr.route_id = r.id
        join stations sf on r.depart_station_id = sf.id
        join stations st on r.dest_station_id = st.id
        join booking_customers bc on (b.id = bc.booking_id and bc.isdefault = "Y")
        join customers c on bc.customer_id = c.id
        left join agents ag on b.agent_id = ag.id

    where :conditions order by b.created_at DESC';

        //$startDate = date_format(date_create('2024-01-01'), 'd/m/Y');
        //$startDate = Carbon::today()->subDays(7)->format('d/m/Y');
        $startDate = date('d/m/Y');
        $endDate = date('d/m/Y');
        $startTravelDate = Carbon::today()->subDays(7)->format('Y-m-d');

        $conditionStr = 'b.agent_id = "'.env('AGENT_ID').'"';
        $dateFillter = true;

        if (!is_null($daterange) && $daterange != '') {
            $dates = explode('-', $daterange);
            $startDate = trim($dates[0]);
            $endDate = trim($dates[1]);
        }

        //$startDateSql = Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d 00:00:00');
        //$endDateSql = Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d 23:59:59');
        $startDateSql = '2025-08-01';
        $endDateSql = '2025-11-30';

        // $conditionStr .= ' and (b.departdate >="' . $startDateSql . '" and b.departdate <="' . $endDateSql . '") ';


        if (!empty($searchText)) {
        }


        if (!is_null($station_from) && $station_from != '') {
            $dateFillter = false;
            $conditionStr .= ' and sf.id = "' . $station_from . '"';
        }
        if (!is_null($station_to) && $station_to != '') {
            $dateFillter = false;
            $conditionStr .= ' and st.id = "' . $station_to . '"';
        }
        if (!is_null($departdate) && $departdate != '') {
            $dateFillter = false;
            $conditionStr .= ' and br.traveldate = "' . $departdate . '"';
        }
        if (!is_null($ticketno) && $ticketno != '') {
            $dateFillter = false;
            $conditionStr .= ' and t.ticketno = "' . $ticketno . '"';
        }
        if (!is_null($bookingno) && $bookingno != '') {
            $dateFillter = false;
            $conditionStr .= ' and b.bookingno = "' . $bookingno . '"';
        }

        if (!empty($paymentno)) {
            $dateFillter = false;
            $conditionStr .= ' and p.paymentno = "' . $paymentno . '"';
        }
        if (!empty($customername)) {
            $dateFillter = false;
            $conditionStr .= ' and c.fullname like "' . $customername . '%"';
        }
        if (!empty($email)) {
            $dateFillter = false;
            $conditionStr .= ' and c.email = "' . $email . '"';
        }
        if (!empty($bookChannel)) {
            $conditionStr .= ' and b.book_channel = "' . $bookChannel . '"';
        }
        if (!empty($tripType)) {
            $dateFillter = false;
            $conditionStr .= ' and b.trip_type = "' . $tripType . '"';
        }

        if (!empty($agent_id)) {
            $conditionStr .= ' and b.agent_id = "' . $agent_id . '"';
        }

        if (!is_null($status) && $status != '') {
            $dateFillter = false;
            $conditionStr .= ' and b.status = "' . $status . '"';
        } else {
            if ($dateFillter) {
                $conditionStr .= ' and b.status not in ("delete","void")';
            }
        }

        if ($dateFillter) {
            $conditionStr .= ' and (b.created_at >="' . $startDateSql . '" and b.created_at <="' . $endDateSql . '") ';
        }



        $sql = str_replace(':conditions', $conditionStr, $sql);
        //dd($sql);
        $bookings = DB::select($sql);
        $bookings = json_decode(json_encode($bookings), true);


        $sections = [];
        $tripTypes = $this->bookingService->getTripType();
        $bookChannels  = $this->bookingService->getBookingChannel();
        $bookingStatus = $this->bookingService->status();

        $agents = Agent::all();

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
            'searchText' => $searchText
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        $booking = Booking::whereId($id)->with(['agent', 'bookingSubRoutes', 'bookingCustomers'])->first()->toArray();

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
}
