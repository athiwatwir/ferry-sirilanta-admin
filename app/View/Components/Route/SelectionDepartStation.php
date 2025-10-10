<?php

namespace App\View\Components\route;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Services\RouteService;


class SelectionDepartStation extends Component
{
    protected $routeService;

    public function __construct(RouteService $routeService)
    {
        $this->routeService = $routeService;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $departStations = $this->routeService->avalDepartStation();
        return view('components.route.selection-depart-station', compact('departStations'));
    }
}
