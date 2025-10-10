<?php

namespace App\Http\Controllers\Api\V1\Back;

use App\Http\Controllers\Controller;
use App\Http\Resources\StationResource;
use App\Models\AgentSubRoute;
use App\Models\Route;
use Illuminate\Http\Request;
use Carbon\Carbon;


class AgentRouteApiController extends Controller
{
    public function index(Request $request)
    {
        $agent = $request->user();
        $departStationId = $request->departure;
        $destStationId = $request->destination;
        $fillter = $request->fillter;

        $routes = Route::with([
            'departStation',
            'destStation',
            'subRoutes' => function ($query) use ($agent, $fillter) {
                $query->whereIn('id', function ($q) use ($agent, $fillter) {
                    $q = $q->select('sub_route_id')
                        ->from('agent_sub_routes')
                        ->where('agent_id', $agent->id);
                    if (($fillter) == 'all') {
                        $q = $q->where('isadded', 'N');
                    } else {
                        $q = $q->where('isadded', 'Y');
                    }
                });
            },
            'subRoutes.agentSubRoutes' => function ($query) use ($agent) {
                $query->where('agent_id', $agent->id);
            }
        ]);


        if (!empty($request->route_id)) {
            $routes = $routes->where('id', $request->route_id);
        }

        if (!empty($departStationId)) {
            $routes = $routes->where('depart_station_id', $departStationId);
        }

        if (!empty($destStationId)) {
            $routes = $routes->where('dest_station_id', $destStationId);
        }

        $routes = $routes->get();


        $data = [];
        //dd($routes);

        foreach ($routes as $route) {
            $subRoutes = [];

            if (sizeof($route->subRoutes) == 0) {
                continue;
            }

            foreach ($route->subRoutes as $subRoute) {

                $icons = [];
                if (!empty($subRoute->icon_set) && is_array($subRoute->icon_set)) {
                    foreach ($subRoute->icon_set as $icon) {
                        $icons[] = asset('images/icon-route/ico-' . $icon . '.png');
                    }
                }
                $agentSubRoute = $subRoute->agentSubRoutes[0];

                $subRoutes[] = [
                    'id' => $subRoute->id,
                    'agent_sub_route_id' => $agentSubRoute->id,
                    'route_id' => $subRoute->route_id,
                    'depart_time' => Carbon::parse($subRoute->depart_time)->format('H:i'),
                    'arrival_time' => Carbon::parse($subRoute->arrival_time)->format('H:i'),
                    'origin_timezone' => $subRoute->origin_timezone,
                    'destination_timezone' => $subRoute->destination_timezone,
                    'boat_type' => $subRoute->boat_type,
                    'seatamt' => $subRoute->seatamt,
                    'type' => $subRoute->type,
                    'icons' => $icons,
                    'isactive' => $agentSubRoute->isactive,
                    'cost_price' => $this->calculatePrice($subRoute->price, $agentSubRoute->discount_type, $agentSubRoute->discount_regular_price),
                    'cost_child_price' => $this->calculatePrice($subRoute->child_price, $agentSubRoute->discount_type, $agentSubRoute->discount_child_price),
                    'cost_infant_price' => $this->calculatePrice($subRoute->infant_price, $agentSubRoute->discount_type, $agentSubRoute->discount_regular_price),
                    'price' => $agentSubRoute->price ?? 0,
                    'child_price' => $agentSubRoute->child_price,
                    'infant_price' => $agentSubRoute->infant_price,

                ];
            }

            $data[] = [
                'id' => $route->id,
                'departure_station' => new StationResource($route->departStation),
                'destination_station' => new StationResource($route->destStation),
                'sub_routes' => $subRoutes
            ];
        }

        return response()->json([
            'success' => true,
            'agent' => $agent,
            'data'    => $data,
        ], 200);
    }

    public function update($agentSubRouteId, Request $request)
    {

        $agent = $request->user();


        $agentSubRoute = AgentSubRoute::whereId($agentSubRouteId)->first();
        if (empty($agentSubRoute)) {
            return response()->json([
                'success' => true,
                'agent' => $agent,
                'agent_sub_route_id' => $agentSubRouteId,
                'msg' => 'Not found data.'
            ], 200);
        }

        $agentSubRoute->update($request->all());

        return response()->json([
            'success' => true,
            'agent' => $agent,
            'agent_sub_route_id' => $agentSubRouteId,
            'data' => $agentSubRoute,
        ], 200);
    }

    private function calculatePrice($price = 0, $discountType = 'percent', $discountValue = 0)
    {
        if (empty($discountValue)) {
            return $price;
        }

        if ($discountType == 'percent') {
            $price = $price - ($price * ($discountValue / 100));
        } else {
            $price = $price - $discountValue;
        }

        return $price;
    }

    public function addedRoute(Request $request)
    {
        $agent = $request->user();
        $data = $request->all();

        if (isset($data['agent_sub_route_ids'])) {
            AgentSubRoute::whereIn('id', $data['agent_sub_route_ids'])->update(['isadded' => 'Y']);
        }

        return response()->json([
            'success' => true,
            'agent' => $agent,
            'data' => []
        ], 200);
    }
}
