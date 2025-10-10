<?php

namespace App\View\Components\Station;

use App\Models\Section;
use App\Models\Station;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Cache;

class Selection extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $options = Cache::remember('section_station_options', 60 * 60, function () {
            $sections = Section::where('isactive', 'Y')
                ->with(['stations' => function ($query) {
                    $query->select('id', 'nickname', 'name_en', 'piername_en', 'section_id');
                }])
                ->select('id', 'name')
                ->orderBy('sort')
                ->get();

            return $sections->mapWithKeys(function ($section) {
                $data = collect($section->stations)->mapWithKeys(function ($station) {
                    $text = sprintf('[%s] %s', $station->nickname, $station->name_en);
                    if (!empty($station->piername_en)) {
                        $text .= sprintf(' (%s)', $station->piername_en);
                    }
                    return [$station->id => $text];
                });

                return [$section->name => $data];
            });
        });

        //dd($options);
        return view('components.station.selection', [
            'options' => $options
        ]);
    }
}
