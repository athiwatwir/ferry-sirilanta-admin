<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\RoutePriceHelper;
use App\Http\Controllers\Controller;
use App\Models\AgentSubRoute;
use App\Models\Route;
use App\Models\SubRoute;
use Illuminate\Http\Request;
use App\Http\Resources\StationResource;
use Carbon\Carbon;


class RouteApiController extends Controller
{
    public function index(Request $request)
    {
        $agent = $request->user();

        $departStationId = $request->depart_station;  // ค่าจาก request
        $destStationId = $request->dest_station;      // ค่าจาก request

        $agentSubRoutesQuery = AgentSubRoute::with([
            'subRoute.route.departStation',
            'subRoute.route.destStation'
        ])
            ->where('agent_id', $agent->id);

        // Filter depart station
        if (!empty($departStationId)) {
            $agentSubRoutesQuery->whereHas('subRoute.route', function ($query) use ($departStationId) {
                $query->where('depart_station_id', $departStationId);
            });
        }

        // Filter destination station
        if (!empty($destStationId)) {
            $agentSubRoutesQuery->whereHas('subRoute.route', function ($query) use ($destStationId) {
                $query->where('dest_station_id', $destStationId);
            });
        }

        $agentSubRoutes = $agentSubRoutesQuery
            ->join('sub_routes', 'agent_sub_routes.sub_route_id', '=', 'sub_routes.id')
            ->orderBy('sub_routes.depart_time', 'asc')
            ->select('agent_sub_routes.*')
            ->get();

        $data = [];

        foreach ($agentSubRoutes as $agentSubRoute) {
            $subRoute = $agentSubRoute->subRoute;

            $route = $subRoute->route;
            $departStation = $route->departStation;
            $destStation = $route->destStation;

            $prices = RoutePriceHelper::agentSubRouteTicketPrice($agentSubRoute->id);

            $icons = [];
            if (!empty($subRoute->icon_set) && is_array($subRoute->icon_set)) {
                foreach ($subRoute->icon_set as $icon) {
                    $icons[] = asset('images/icon-route/ico-' . $icon . '.png');
                }
            }


            $data[] = [
                'id' => $subRoute->id,
                'departure_time' => Carbon::parse($subRoute->depart_time)->format('H:i'),
                'arrival_time' => Carbon::parse($subRoute->arrival_time)->format('H:i'),
                'departure_timezone' => $subRoute->origin_timezone,
                'arrival_timezone' => $subRoute->destination_timezone,
                'boat_type' => $subRoute->boat_type,
                'seatamt' => $subRoute->seatamt,
                'departure_station' => StationResource::collection([$departStation])[0],
                'destination_station' => StationResource::collection([$destStation])[0],
                'prices' => $prices,
                'icons' => $icons
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => $data,
            'params' => [
                'depart_station' => $departStationId,
                'dest_station' => $destStationId
            ]
        ], 200);
    }


    public function getRoute(Request $request, $subRouteId)
    {
        $agent = $request->user();
        $subRoute = SubRoute::whereId($subRouteId)->with(['route.departStation', 'route.destStation'])->first();

        $agentSubRoute = AgentSubRoute::where('agent_id', $agent->id)->where('sub_route_id', $subRouteId)->first();

        $prices = RoutePriceHelper::agentSubRouteTicketPrice($agentSubRoute->id);
        $data = [
            'id' => $subRoute->id,
            'departure_time' => Carbon::parse($subRoute->depart_time)->format('H:i'),
            'arrival_time' => Carbon::parse($subRoute->arrival_time)->format('H:i'),
            'departure_timezone' => $subRoute->origin_timezone,
            'arrival_timezone' => $subRoute->destination_timezone,
            'boat_type' => $subRoute->boat_type,
            'seatamt' => $subRoute->seatamt,
            'departure_station' => StationResource::collection([$subRoute->route->departStation])[0],
            'destination_station' => StationResource::collection([$subRoute->route->destStation])[0],
            'prices' => $prices
        ];

        return response()->json([
            'success' => true,
            'data'    => $data,
        ], 200);
    }
}
