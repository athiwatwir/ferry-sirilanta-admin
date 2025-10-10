<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\SubRoute;
use Illuminate\Http\Request;
use PHPUnit\Event\Code\IssueTrigger\SelfTrigger;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class RouteController extends Controller
{
    public static function ferryTypes()
    {
        return [
            'bus' => 'Bus',
            'car-ferry' => 'Car Ferry',
            'catamaran' => 'Catamaran',
            'cruise-ship' => 'Cruise Ship',
            'ferry' => 'Ferry',
            'high-speed-ferry' => 'High Speed Ferry',
            'long-tail-boat' => 'Long Tail Boat',
            'speed-boat' => 'Speed Boat',
            'van' => 'Van',
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $depart_station_id = request()->depart_station_id;
        $dest_station_id = request()->dest_station_id;

        // ดึง route_id ออกมาก่อน
        $routeIds = DB::table('agent_sub_routes')
            ->join('sub_routes', 'agent_sub_routes.sub_route_id', '=', 'sub_routes.id')
            ->where('agent_sub_routes.agent_id', env("AGENT_ID"))
            ->pluck('sub_routes.route_id')
            ->unique()
            ->toArray();

        $routes = Route::with([
            'departStation.section',
            'subRoutes'
        ])
            ->join('stations as depart', 'routes.depart_station_id', '=', 'depart.id')
            ->join('sections', 'depart.section_id', '=', 'sections.id')
            ->whereIn('routes.id', $routeIds)
            ->orderBy('sections.sort', 'asc')
            ->select('routes.*');

        if ($depart_station_id) {
            $routes = $routes->where('routes.depart_station_id', $depart_station_id);
        }

        if ($dest_station_id) {
            $routes = $routes->where('routes.dest_station_id', $dest_station_id);
        }

        $routes = $routes->get();


        return view('pages.route.index', [
            'title' => 'Route',
            'routes' => $routes,
            'depart_station_id' => $depart_station_id,
            'dest_station_id' => $dest_station_id
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.route.create', [
            'title' => 'Create Main Route',
            'breadcrumbs' => [
                'Main Route List' => route('route.index'),
                'Create' => ''
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'depart_station_id' => 'required|string',
            'dest_station_id' => 'required|string',
        ]);

        if (self::checkDuplicate($request->depart_station_id, $request->dest_station_id)) {
            //session()->flash('error', 'Route นี้มีอยู่แล้ว');
            $route = Route::where('depart_station_id', $request->depart_station_id)->where('dest_station_id', $request->dest_station_id)->first();

            return redirect()->route('route.edit', ['route' => $route]);
        }

        $route = Route::create($request->all());

        if ($route) {
            session()->flash('success', __('messages.saved'));
            $this->clearCache();

            return redirect()->route('route.edit', ['route' => $route]);
        }
        return redirect()->route('route.index');
    }

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
        $route = Route::where('id', $id)->with(['departStation', 'destStation', 'subRoutes'])->first();



        return view('pages.route.edit', [
            'title' => 'Route Management',
            'route' => $route,
            'ferryTypes' => RouteController::ferryTypes(),
            'breadcrumbs' => [
                'Route List' => route('route.index'),
                'Edit Route' => ''
            ]
        ]);
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
        Route::whereId($id)->delete();
        SubRoute::where('route_id', $id)->delete();
        session()->flash('success', __('messages.deleted'));
        $this->clearCache();

        return redirect()->route('route.index');
    }

    public function changeStatus(Request $request, $id)
    {
        // $id = $request->id;
        $route = Route::where('id', $id)->first();

        if ($route) {
            $route->isactive = $route->isactive == 'Y' ? 'N' : 'Y';
            $route->save();

            SubRoute::where('route_id', $route->id)->update(['isactive' => $route->isactive]);
            session()->flash('success', __('messages.updated'));

            $this->clearCache();
        }
        return redirect()->back();
    }

    private function checkDuplicate($depart_station_id, $dest_station_id, $routeId = null)
    {
        $count = Route::where('depart_station_id', $depart_station_id)->where('dest_station_id', $dest_station_id);
        if (!empty($routeId)) {
            $count = $count->whereNot('id', $routeId);
        }
        $count = $count->count();
        return $count > 0 ? true : false;
    }


    private function clearCache()
    {
        Cache::forget('depart_station_service');
        Cache::forget('dest_station_service');
    }


    /**
     * Internal api
     */

    public function routes()
    {
        $departStationId = request()->depart_station_id;
        $destStationId = request()->dest_station_id;

        $routes = Route::with(['departStation', 'destStation', 'subRoutes'])->where('isactive', 'Y')->whereHas('subRoutes');
        if (!empty($departStationId)) {
            $routes = $routes->where('depart_station_id', $departStationId);
        }

        if (!empty($destStationId)) {
            $routes = $routes->where('dest_station_id', $destStationId);
        }

        $routes = $routes->orderBy('depart_station_id')->get();

        $data = [];
        foreach ($routes as $route) {
            $_subRoute = [];
            foreach ($route->subRoutes as $item) {
                $_subRoute[] = [
                    'sub_route_id' => $item->id,
                    'depart_time' => $item->depart_time->format('H:i'),
                    'arrival_time' => $item->arrival_time->format('H:i'),
                ];
            }

            $data[] = [
                'depart_station_name' => sprintf('%s %s', $route->departStation->name_en, $route->departStation->piername_en),
                'dest_station_name' => sprintf('%s %s', $route->destStation->name_en, $route->destStation->piername_en),
                'sub_routes' => $_subRoute
            ];
        }
        return response()->json([
            'message' => 'บันทึกเรียบร้อย',
            'data' => $data,
        ], 200);
    }
}
