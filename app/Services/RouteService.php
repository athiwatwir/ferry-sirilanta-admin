<?php

namespace App\Services;

use App\Models\Route;
use App\Models\RouteScheduleDaily;
use App\Models\SubRoute;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RouteService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function avalDepartStation($agent = null)
    {

        $data = Cache::remember('depart_station_service', 1440, function () {
            $results = DB::table('sections as sec')
                ->join('stations as s', 'sec.id', '=', 's.section_id')
                ->select('sec.name as section_name', 's.*')
                ->where('sec.isactive', 'Y')
                ->where('s.isactive', 'Y')
                ->whereIn('s.id', function ($query) {
                    $query->select('depart_station_id')
                        ->from('routes')
                        ->where('isactive', 'Y');
                })
                ->orderBy('sec.sort', 'asc')
                ->orderBy('s.sort', 'asc')
                ->get();

            $grouped = [];

            foreach ($results as $item) {
                if (!isset($grouped[$item->section_name])) {
                    $grouped[$item->section_name] = [];
                }

                $grouped[$item->section_name][] = [
                    'id' => $item->id,
                    'name_en' => $item->name_en,
                    'name_th' => $item->name_th,
                    'piername_en' => $item->piername_en,
                    'piername_th' => $item->piername_th,
                    'nickname' => $item->nickname,
                    'type' => $item->type,
                ];
            }

            return $grouped;
        });


        return $data;
    }


    public function avalDestStation($departStationId = null, $agent = null)
    {

        $results = DB::table('sections as sec')
            ->join('stations as s', 'sec.id', '=', 's.section_id')
            ->select('sec.name as section_name', 's.*')
            ->where('sec.isactive', 'Y')
            ->where('s.isactive', 'Y');
        if (!empty($departStationId)) {
            $results = $results->whereIn('s.id', function ($query) use ($departStationId) {
                $query->select('dest_station_id')
                    ->from('routes')
                    ->where('isactive', 'Y')
                    ->where('depart_station_id', $departStationId);
            });
        } else {
            $results = $results->whereIn('s.id', function ($query) {
                $query->select('dest_station_id')
                    ->from('routes')
                    ->where('isactive', 'Y');
            });
        }

        $results = $results->orderBy('sec.sort', 'asc')
            ->orderBy('s.sort', 'asc')
            ->get();

        $grouped = [];

        foreach ($results as $item) {
            if (!isset($grouped[$item->section_name])) {
                $grouped[$item->section_name] = [];
            }

            $grouped[$item->section_name][] = [
                'id' => $item->id,
                'name_en' => $item->name_en,
                'name_th' => $item->name_th,
                'piername_en' => $item->piername_en,
                'piername_th' => $item->piername_th,
                'nickname' => $item->nickname,
                'type' => $item->type,
            ];
        }

        return $grouped;
    }


    public function avalRoutes($departStationId, $destStationId, $agentId = null, $travelDate = null)
    {

        if (empty($travelDate)) {
            $travelDate = Carbon::now()->format('Y-m-d');
        }

        $route = Route::where('depart_station_id', $departStationId)->where('dest_station_id', $destStationId)->with(['departStation', 'destStation'])->first();

        if (empty($route)) {
            return [];
        }
        $subRoutes = [];

        if (empty($agentId)) {
            $subRoutes = SubRoute::where('route_id', $route->id)->where('isactive', 'Y')->orderBy('depart_time')->get();
        } else {
        }

        $data = [];

        foreach ($subRoutes as $item) {
            if (!self::checkAvaliable($item->id, $travelDate)) {
                continue;
            }
            $data[] = [
                'depart_station' => [
                    'name_en' => $route->departStation->name_en,
                    'name_th' => $route->departStation->name_th,
                    'piername_en' => $route->departStation->piername_en,
                    'piername_th' => $route->departStation->piername_th,
                    'nickname' => $route->departStation->nickname,
                    'type' => $route->departStation->type,
                ],
                'dest_station' => [
                    'name_en' => $route->destStation->name_en,
                    'name_th' => $route->destStation->name_th,
                    'piername_en' => $route->destStation->piername_en,
                    'piername_th' => $route->destStation->piername_th,
                    'nickname' => $route->destStation->nickname,
                    'type' => $route->destStation->type,
                ],
                'depart_time' => $item->depart_time->format('H:i'),
                'arrival_time' => $item->arrival_time->format('H:i'),
                'depart_time_zone' => $item->origin_timezone,
                'arrival_time_zone' => $item->destination_timezone,
                'regular_price' => $item->price,
                'child_price' => $item->child_price,
                'infant_price' => $item->infant_price,
                'boat_type' => $item->boat_type,
                'text_1' => $item->text_1,
                'text_2' => $item->text_2,
                'master_from' => $item->master_from,
                'master_to' => $item->master_to,
                'info_from' => $item->info_from,
                'info_to' => $item->info_to,
            ];
        }


        return $data;
    }


    public function checkAvaliable($subRouteId, $travelDate = '')
    {
        if (empty($travelDate)) {
            $travelDate = Carbon::now()->format('Y-m-d');
        }

        $count = RouteScheduleDaily::where('sub_route_id', $subRouteId)->where('date', $travelDate)->where('isopen', 'Y')->count();
        if ($count > 0) {
            return true;
        }

        return false;
    }
}
