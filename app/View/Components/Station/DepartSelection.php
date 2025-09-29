<?php

namespace App\View\Components\Station;

use App\Services\StationService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DepartSelection extends Component
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
        $sections = app(StationService::class)->getDepart('Y');
        //dd($sections);
        return view('components.station.depart-selection', ['sections' => $sections]);
    }
}
