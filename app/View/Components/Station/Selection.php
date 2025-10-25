<?php

namespace App\View\Components\Station;

use App\Models\Section;
use App\Models\Station;
use Closure;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Selection extends Component
{
    public $type;
    public $agentId;
    public $user;
    /**
     * Create a new component instance.
     */
    public function __construct(string $type = '', string $agentId = '')
    {
        $this->type = $type;
        $this->agentId = $agentId;

        $this->user = Auth::user();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $stationIds = null;

        $agentId = $this->user['agent_id'];

        if ($this->type == 'depart') {
            $stationIds = DB::table('agent_sub_routes as asr')
                ->join('sub_routes as sr', 'asr.sub_route_id', '=', 'sr.id')
                ->join('routes as r', 'sr.route_id', '=', 'r.id')
                ->where('asr.agent_id', $agentId)
                ->groupBy('r.depart_station_id')
                ->pluck('r.depart_station_id');
        } elseif ($this->type == 'dest') {
            $stationIds = DB::table('agent_sub_routes as asr')
                ->join('sub_routes as sr', 'asr.sub_route_id', '=', 'sr.id')
                ->join('routes as r', 'sr.route_id', '=', 'r.id')
                ->where('asr.agent_id', $agentId)
                ->groupBy('r.dest_station_id')
                ->pluck('r.dest_station_id');
        }

        $sections = Section::where('isactive', 'Y')
            ->whereHas('stations', function ($query) use ($stationIds) {
                $query->when($stationIds && $stationIds->isNotEmpty(), function ($q) use ($stationIds) {
                    $q->whereIn('id', $stationIds);
                });
            })
            ->with(['stations' => function ($query) use ($stationIds) {
                $query->select('id', 'nickname', 'name_en', 'piername_en', 'section_id')
                    ->when($stationIds && $stationIds->isNotEmpty(), function ($q) use ($stationIds) {
                        $q->whereIn('id', $stationIds);
                    });
            }])
            ->select('id', 'name')
            ->orderBy('sort')
            ->get();

        $options = $sections->mapWithKeys(function ($section) {
            $data = collect($section->stations)->mapWithKeys(function ($station) {
                $text = sprintf('[%s] %s', $station->nickname, $station->name_en);
                if (!empty($station->piername_en)) {
                    $text .= sprintf(' (%s)', $station->piername_en);
                }
                return [$station->id => $text];
            });

            return [$section->name => $data];
        });

        return view('components.station.selection', [
            'options' => $options
        ]);
    }
}
