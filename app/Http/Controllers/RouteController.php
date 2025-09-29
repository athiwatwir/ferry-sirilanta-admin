<?php

namespace App\Http\Controllers;

use App\Services\RouteService;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = app(RouteService::class)->getRoutes();

        //$routes = json_decode(json_encode($routes));
        //dd($routes);
        return view('pages.route.index', [
            'title' => 'All Routes',
            'routes' => $routes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $routes = app(RouteService::class)->getRoutes('all');

        return view('pages.route.create', [
            'title' => 'Add Routes',
            'routes' => $routes,
            'breadcrumbs' => [
                'All Routes' => route('route.index'),
                'Add Routes' => ''
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = ($request->all());
        $route = app(RouteService::class)->addedRoute($data);

        session()->flash('success', __('messages.saved'));
        return redirect()->route('route.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $route = app(RouteService::class)->getRoute($id);

        //$routes = json_decode(json_encode($routes));
        //dd($routes);
        return view('pages.route.show', [
            'title' => 'Route',
            'route' => $route,
            'breadcrumbs' => [
                'Route List' => route('route.index'),
                'Show Route' => ''
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    /**
     * Internal services
     */

    public function saveAgentRoute(Request $request)
    {
        $data = $request->all();

        $route = app(RouteService::class)->updateRoute($data['agent_sub_route_id'], $data);

        return response()->json([
            'message' => 'บันทึกเรียบร้อย',
            'data' => $route,
        ], 200);
    }
}
