<?php

namespace App\Http\Controllers;

use App\Helpers\UtilHelper;
use App\Services\RouteService;
use Illuminate\Http\Request;


class DemoController extends Controller
{

    protected $routeService;

    public function __construct(RouteService $routeService)
    {
        $this->routeService = $routeService;
    }
    public function index()
    {

        return view('pages.demo.index');
    }

    public function route()
    {
        $agentId = request()->agent;
        $departStationId = request()->depart;
        $destStationId = request()->dest;
        $_travelDate = request()->travel_date;

        $travelDate = UtilHelper::dmYToYmd($_travelDate);

        $departStations = $this->routeService->avalDepartStation();

        $destStations = [];
        if (!empty($departStationId)) {
            $destStations = $this->routeService->avalDestStation($departStationId);
        }

        $routes = [];
        if (!empty($departStationId) && !empty($destStationId)) {
            $routes = $this->routeService->avalRoutes($departStationId, $destStationId, null, $travelDate);
        }

        return view('pages.demo.route', [
            'agentId' => $agentId,
            'departStations' => $departStations,
            'destStations' => $destStations,
            'departStationId' => $departStationId,
            'destStationId' => $destStationId,
            'routes' => $routes,
            'travelDate' => $_travelDate
        ]);
    }
}
