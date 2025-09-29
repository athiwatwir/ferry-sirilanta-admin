<?php

namespace App\View\Components\Station;

use App\Services\StationService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

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
        $stations = app(StationService::class)->getDepart();
        return view('components.station.selection');
    }
}
