<?php

namespace App\View\Components\Selection;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Cache;

class TimeZone extends Component
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

        $timezones = Cache::remember('timezone_list_sorted', now()->addDay(), function () {
            return collect(DateTimeZone::listIdentifiers())
                ->map(function ($tz) {
                    $dt = new DateTime("now", new DateTimeZone($tz));
                    $offset = $dt->getOffset(); // วัดเป็นวินาที
                    return [
                        'name' => $tz,
                        'offset' => $offset,
                        'label' => '(UTC' . ($offset >= 0 ? '+' : '') . ($offset / 3600) . ') ' . $tz,
                    ];
                })
                ->sortBy('offset') // 🔁 เรียงจาก UTC- ไป +
                ->pluck('label', 'name'); // ['Asia/Bangkok' => '(UTC+7) Asia/Bangkok', ...]
        });


        return view('components.selection.time-zone', compact('timezones'));
    }
}
