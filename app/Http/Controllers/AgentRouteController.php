<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentSubRoute;
use App\Models\Route;
use App\Models\SubRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgentRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $agentId = request()->agent;
        $depart_station_id = request()->depart_station_id;
        $dest_station_id = request()->dest_station_id;

        $agent = Agent::whereId($agentId)->first();

        /*
        $routes = Route::with([
            'departStation.section',
        ])
            ->join('stations as depart', 'routes.depart_station_id', '=', 'depart.id')
            ->join('sections', 'depart.section_id', '=', 'sections.id')
            ->where('routes.isactive', 'Y')
            ->orderBy('sections.sort', 'asc')
            ->select('routes.*')
            ->get();
            */
        $subRouteIds = AgentSubRoute::where('agent_id', $agent->id)->get()->pluck('sub_route_id')->toArray();
        $routes = Route::select('routes.*')
            ->join('stations as depart', 'routes.depart_station_id', '=', 'depart.id')
            ->with([
                'departStation',
                'destStation',
                'subRoutes' => function ($query) use ($subRouteIds) {
                    $query->whereNotIn('id', $subRouteIds);
                }
            ])
            ->orderBy('depart.name_en');

        if ($depart_station_id) {
            $routes = $routes->where('routes.depart_station_id', $depart_station_id);
        }

        if ($dest_station_id) {
            $routes = $routes->where('routes.dest_station_id', $dest_station_id);
        }

        $routes = $routes->get();


        return view('pages.agent-route.create', [
            'routes' => $routes,
            'agentId' => $agentId,
            'breadcrumbs' => [
                'All agents' => route('agent.index'),
                'Agent Routes' => route('agent.route', ['agent' => $agent]),
                'Select Route' => ''
            ],
            'depart_station_id' => $depart_station_id,
            'dest_station_id' => $dest_station_id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $agentId = $request->agent_id;
        $selectedSubRouteIds = $request->sub_route_ids;
        $allData = [];
        $now = now();

        foreach ($selectedSubRouteIds as $sub_route_id) {
            $allData[] = [
                'id' => (string) Str::uuid(),
                'agent_id' => $agentId,
                'sub_route_id' => $sub_route_id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        AgentSubRoute::insert($allData);

        return redirect()->route('agent.route', ['agent' => $agentId]);
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
        $agentRoute = AgentSubRoute::whereId($id)->with('subRoute')->first();
        $agentId = $agentRoute->agent_id;
        $agentRoute->delete();


        return redirect()->route('agent.route', ['agent' => $agentId]);
    }





    /**
     * Internal services
     */

    public function saveAgentRoute(Request $request)
    {
        $data = $request->all();
        $agentSubRoute = AgentSubRoute::whereId($request->agent_sub_route_id)->first();


        if (empty($agentSubRoute)) {
            return response()->json([
                'error' => 'Unprocessable Entity',
                'message' => 'Validation failed.',
                'details' => $agentSubRoute
            ], 422);
        }

        $agentSubRoute->update([
            'discount_type' => $request->discount_type,
            'discount_regular_price' => $request->discount_regular_price ?? 0,
            'discount_child_price' => $request->discount_child_price ?? 0,
            'discount_infant_price' => $request->discount_infant_price ?? 0,
        ]);

        return response()->json([
            'message' => 'บันทึกเรียบร้อย',
            'data' => $data,
        ], 200);
    }
}
