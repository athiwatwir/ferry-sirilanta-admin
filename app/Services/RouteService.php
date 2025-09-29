<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RouteService
{

    /**
     * Create a new class instance.
     */
    public function __construct() {}


    public function getRoutes($fillter = '')
    {
        $routes = app(ApiService::class)->get('/back/agent-route', ['fillter' => $fillter]);

        return ($routes['data']);
    }

    public function getRoute($routeId)
    {
        $routes = app(ApiService::class)->get('/back/agent-route', ['route_id' => $routeId]);

        if (isset($routes['data'][0])) {
            return ($routes['data'][0]);
        }
        return ($routes['data']);
    }

    public function updateRoute($agentRouteId, $data)
    {
        $route = app(ApiService::class)->post('/back/agent-route/' . $agentRouteId, $data);
        if (isset($route['data'][0])) {
            return ($route['data'][0]);
        }
        return ($route['data']);
    }

    public function addedRoute($data)
    {
        $route = app(ApiService::class)->post('/back/agent-route/added-route', $data);

        if (isset($route['data'][0])) {
            return ($route['data'][0]);
        }
        return ($route['data']);
    }
}
