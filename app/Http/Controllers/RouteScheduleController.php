<?php

namespace App\Http\Controllers;

use App\Helpers\CalendarHelper;
use App\Helpers\UtilHelper;
use App\Models\RouteSchedule;
use App\Models\RouteScheduleDaily;
use App\Models\SubRoute;
use App\Models\Template;
use App\Models\TemplateLine;
use App\Services\RouteScheduleService;
use App\Services\RouteService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class RouteScheduleController extends Controller
{
    protected $routeService;
    protected $routeScheduleService;

    public function __construct(RouteService $routeService, RouteScheduleService $routeScheduleService)
    {
        $this->routeService = $routeService;
        $this->routeScheduleService = $routeScheduleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Route Schedule';

        $depart_station_id = request()->depart_station_id;
        $dest_station_id = request()->dest_station_id;

        //$routeSchedules = RouteSchedule::orderBy('created_at')->get();

        $subRoutes = SubRoute::with(['lastSchedules'])
            ->whereIn('id', function ($query) {
                $query->select('sub_route_id')
                    ->from('route_schedules')
                    ->groupBy('sub_route_id');
            })->orderBy('depart_time', 'ASC')->get();


        return view('pages.route-schedule.index', compact('title', 'subRoutes', 'depart_station_id', 'dest_station_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $templates = Template::with(['templateLines'])->get();

        $title = 'Create Route Schedule';
        return view('pages.route-schedule.create', [
            'title' => $title,
            'breadcrumbs' => [
                'All Route Schedule' => route('routeSchedule.index'),
                'Create Route Schedule' => ''
            ],
            'templates' => $templates
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $subRouteIds = $request->sub_route_ids;
        $startDate = UtilHelper::dmYToYmd($request->startdate);
        $enddate = UtilHelper::dmYToYmd($request->enddate);
        $dayOfWeeks = $request->days;
        $isopen = $request->isopen;
        $templateIds = $request->template_ids;

        if (!empty($templateIds) && count($templateIds) > 0) {
            $subRouteIds = TemplateLine::whereIn('template_id', $templateIds)
                ->distinct()
                ->pluck('sub_route_id')
                ->toArray();
        }

        foreach ($subRouteIds as $subRouteId) {
            $insertData[] = [
                'id' => (string) Str::uuid(),
                'isopen' => $isopen,
                'day_of_week' => json_encode($dayOfWeeks),
                'startdate' => $startDate,
                'enddate' => $enddate,
                'sub_route_id' => $subRouteId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        RouteSchedule::insert($insertData);

        $this->routeScheduleService->dailyProcess($startDate, $enddate, $dayOfWeeks, $isopen, $subRouteIds);
        session()->flash('success', __('messages.saved'));
        return redirect()->route('routeSchedule.index');
    }

    public function processing($id) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        RouteScheduleDaily::where('route_schedule_id', $id)->delete();
        RouteSchedule::whereId($id)->delete();

        session()->flash('success', __('messages.deleted'));
        return redirect()->route('routeSchedule.index');
    }


    public function calendar($subRouteId)
    {

        $months = [];
        $start = Carbon::now()->subMonths(2)->startOfMonth(); // 2 เดือนก่อน
        $end = Carbon::now()->addMonths(10)->startOfMonth();  // 10 เดือนข้างหน้า

        while ($start <= $end) {
            $months[] = $start->format('Y-m-01'); // หรือ 'Y-m-d' ถ้าอยากได้วันเต็ม
            $start->addMonth();
        }

        $subRoute = SubRoute::whereId($subRouteId)->with(['route'])->first();

        $dailyItems = RouteScheduleDaily::where('sub_route_id', $subRouteId)
            ->where('isopen', 'Y')
            ->orderBy('date', 'ASC')
            ->orderBy('created_at', 'DESC') // เอาอันใหม่สุดในวันที่ซ้ำ
            ->get()
            ->unique('date') // เก็บเฉพาะรายการแรกที่เจอของแต่ละ date
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        //dd($dailyItems);

        //dd(CalendarHelper::getMonthCalendar('2025-06-01'));
        return view('pages.route-schedule.calendar', [
            'months' => $months,
            'dailyItems' => $dailyItems,
            'subRoute' => $subRoute,
            'breadcrumbs' => [
                'All Route Schedule' => route('routeSchedule.index'),
                'View by Calendar' => ''
            ]
        ]);
    }


    public function dailyStore(Request $request, $id)
    {
        $request->validate([
            'startdate' => 'required|string',
            'enddate' => 'required|string',
        ]);

        $ids = [];
        array_push($ids, $id);

        // dd($request->all());

        $this->routeScheduleService->dailyProcess($request->startdate, $request->enddate, [], $request->isopen, $ids);
        session()->flash('success', __('messages.saved'));
        return redirect()->route('routeSchedule.calendar', ['subRoute' => $id]);
    }
}
