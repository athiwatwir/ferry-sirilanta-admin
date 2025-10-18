<?php

namespace App\Services;

class RouteService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getRoutes($from, $to)
    {

        $routes = app(ApiService::class)->get('/route', ['depart_station' => $from, 'dest_station' => $to]);
        //dd($routes);
        return ($routes['data']);
    }

    public function getRoute($subRouteId)
    {
        $url = sprintf('/route/%s', $subRouteId);
        $route = app(ApiService::class)->get($url, []);
        return ($route['data']);
    }
}
