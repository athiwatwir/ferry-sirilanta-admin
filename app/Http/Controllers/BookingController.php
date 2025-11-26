<?php

namespace App\Http\Controllers;

use App\Helpers\UtilHelper;
use App\Models\Agent;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\RouteService;

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
            ->leftJoin('agents as ag', 'b.agent_id', '=', 'ag.id')
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
                'b.status',
                'b.ispremiumflex',
                'b.isemailsent',
                'b.referenceno',
                'ag.name as agent_name',
                'ag.code as agent_code',
                'b.agent_id',
                'ag.logo as agent_logo'
            );

        $query->where('b.agent_id', $agentId);
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
            if ($request->filled('agent_id')) {
                $query->where('b.agent_id', $request->agent_id);
            }
            if ($request->filled('status')) {
                $query->where('b.status', $request->status);
            } else {
                $query->whereNotIn('b.status', ['delete', 'void', 'VO']);
            }

            //Default with last 7 days
            $startDate = Carbon::now()->subDays(6)->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');

            if (!empty($daterange)) {
                $daterangeConvert = UtilHelper::parseDateRange($daterange);
                $startDate = $daterangeConvert[0];
                $endDate = $daterangeConvert[1];

                if ($date_type == 'booking_date') {
                    $query->whereBetween('b.created_at', [$startDate, $endDate]);
                    $bookings = $query->orderBy('b.created_at', 'DESC')->get();
                } else {
                    $query->whereBetween('br.traveldate', [$startDate, $endDate]);
                    $bookings = $query->orderBy('br.traveldate', 'DESC')->get();
                }
            } else {
                $query->whereBetween('b.created_at', [$startDate, $endDate]);
                $bookings = $query->orderBy('b.created_at', 'DESC')->get();
            }
        }

        $bookings = json_decode(json_encode($bookings), true);

        $sections = [];
        $tripTypes = $this->bookingService->getTripType();
        $bookChannels  = $this->bookingService->getBookingChannel();
        $bookingStatus = $this->bookingService->status();

        $agents = Agent::where('type', 'API')->get();

        if (request()->ispdf == 'Y') {
            $pdf = Pdf::loadView('pages.booking.pdf.booking', [
                'bookings' => $bookings,
                'daterange' => $daterange,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'date_type' => $date_type == 'booking_date' ? 'Booking Date' : 'Travel Date'
            ])
                ->setOption([
                    'dpi' => 150,
                ])
                ->setPaper('A4', 'landscape'); // ✅ ใช้ A4 แนวนอน

            return $pdf->stream('booking_list.pdf');
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
            'date_type' => $date_type
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
