<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StationResource;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Section;

class StationApiController extends Controller
{

    public function index(Request $request)
    {
        $stations = Station::where('isactive', 'Y')->orderBy('name_en')->get();

        return response()->json([
            'success' => true,
            'data'    => StationResource::collection($stations),
        ], 200);
    }

    public function getStation(Request $request, $id)
    {
        $station = Station::where('isactive', 'Y')->whereId($id)->first();

        return response()->json([
            'success' => true,
            'data'    => StationResource::collection([$station])[0],
        ], 200);
    }

    public function departure(Request $request)
    {
        $agent = $request->user();
        $cacheKey = 'stations_departure_agent_' . $agent->id;

        $group = $request->group;

        $stations = Station::select('stations.*', 'sec.name as section_name', 'sec.name_th as section_name_th')
            ->distinct()
            ->join('routes as r', 'r.depart_station_id', '=', 'stations.id')
            ->join('sections as sec', 'sec.id', '=', 'stations.section_id')
            ->join('sub_routes as sr', 'sr.route_id', '=', 'r.id')
            ->join('agent_sub_routes as asr', 'asr.sub_route_id', '=', 'sr.id')
            ->where('asr.agent_id', $agent->id)
            ->get();

        $data = [];

        if ($group == 'Y') {

            foreach ($stations as $station) {
                if (!isset($data[$station->section_name])) {
                    $data[$station->section_name]['sections']['name'] = $station->section_name;
                    $data[$station->section_name]['sections']['name_th'] = $station->section_name_th;
                }

                $data[$station->section_name]['stations'][] = [
                    'id' => $station->id,
                    'name' => $station->name_en,
                    'name_th' => $station->name_th,
                    'piername' => $station->piername_en,
                    'piername_th' => $station->piername_th,
                    'nickname' => $station->nickname,
                    'type' => $station->type
                ];
            }

            return response()->json([
                'success' => true,
                'data'    => $data,
            ], 200);
        } else {

            return response()->json([
                'success' => true,
                'data'    => StationResource::collection($stations),
            ], 200);
        }
    }

    public function destination(Request $request)
    {
        $agent = $request->user();

        if (empty($request->depart_station)) {
            return response()->json(['message' => 'depart_station is required'], 401);
        }
        $depart_station_id = $request->depart_station;

        $agent = $request->user();
        $cacheKey = 'stations_dest_agent_' . $agent->id;

        $group = $request->group;

        $stations = Station::select('stations.*', 'sec.name as section_name', 'sec.name_th as section_name_th')
            ->distinct()
            ->join('routes as r', 'r.dest_station_id', '=', 'stations.id')
            ->join('sections as sec', 'sec.id', '=', 'stations.section_id')
            ->join('sub_routes as sr', 'sr.route_id', '=', 'r.id')
            ->join('agent_sub_routes as asr', 'asr.sub_route_id', '=', 'sr.id')
            ->where('asr.agent_id', $agent->id)
            ->where('r.depart_station_id', $depart_station_id)
            ->get();
        //dd($stations);

        if ($group == 'Y') {
            $data = [];

            foreach ($stations as $station) {
                if (!isset($data[$station->section_name])) {
                    $data[$station->section_name]['sections']['name'] = $station->section_name;
                    $data[$station->section_name]['sections']['name_th'] = $station->section_name_th;
                }

                $data[$station->section_name]['stations'][] = [
                    'id' => $station->id,
                    'name' => $station->name_en,
                    'name_th' => $station->name_th,
                    'piername' => $station->piername_en,
                    'piername_th' => $station->piername_th,
                    'nickname' => $station->nickname,
                    'type' => $station->type
                ];
            }

            return response()->json([
                'success' => true,
                'data'    => $data,
                'depart_station' => $depart_station_id
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'data'    => StationResource::collection($stations),
                'depart_station' => $depart_station_id
            ], 200);
        }
    }
}
