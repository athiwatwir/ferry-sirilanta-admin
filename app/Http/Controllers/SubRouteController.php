<?php

namespace App\Http\Controllers;

use App\Models\BookingSubRoute;
use App\Models\PriceStrategy;
use App\Models\PriceStrategyLine;
use App\Models\Route;
use App\Models\SubRoute;
use Illuminate\Http\Request;

class SubRouteController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $title = 'Create Route Time';
        $routeId = request()->route;
        $route = Route::whereId($routeId)->with(['departStation', 'destStation'])->first();
        $ferryTypes = RouteController::ferryTypes();
        return view('pages.route.sub-route.create', compact('route', 'ferryTypes', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'depart_time' => 'required|string',
            'arrival_time' => 'required|string',
            'origin_timezone' => 'required|string',
            'destination_timezone' => 'required|string',
            'seatamt' => 'required|integer',
            'price' => 'required|numeric',
            'child_price' => 'required|numeric',
            'infant_price' => 'required|numeric',
        ]);

        $subRoute = SubRoute::create($request->all());

        if ($subRoute) {
            session()->flash('success', __('messages.saved'));
        }

        return redirect()->route('route.edit', ['route' => $request->route_id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subRoute = SubRoute::whereId($id)->with(['route', 'priceStrategy'])->first();

        return view('pages.route.sub-route.modal-view', [
            'title' => '',
            'subRoute' => $subRoute,
            'priceStrategy' => $subRoute->priceStrategy,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subRoute = SubRoute::whereId($id)->with(['priceStrategy'])->first();

        $priceStrategies = PriceStrategy::where('isactive', 'Y')->where('ismaster', 'Y')->get()->pluck('name', 'id');

        return view('pages.route.sub-route.edit', [
            'title' => 'Edit Route Time',
            'subRoute' => $subRoute,
            'priceStrategy' => $subRoute->priceStrategy,
            'priceStrategies' => $priceStrategies,
            'calculateMethods' => PriceStrategyController::$CalculateMethods,
            'calculateTypes' => PriceStrategyController::$CalculateTypes,
            'ferryTypes' => RouteController::ferryTypes(),
            'breadcrumbs' => [
                'Route List' => route('route.index'),
                'Edit Route' => route('route.edit', ['route' => $subRoute->route_id]),
                'Edit Route Time' => ''
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'depart_time' => 'required|string',
            'arrival_time' => 'required|string',
            'origin_timezone' => 'required|string',
            'destination_timezone' => 'required|string',
            'seatamt' => 'required|integer',
            'price' => 'required|numeric',
            'child_price' => 'required|numeric',
            'infant_price' => 'required|numeric',
        ]);




        SubRoute::whereId($id)->update($request->only([
            'close_time',
            'depart_time',
            'arrival_time',
            'origin_timezone',
            'destination_timezone',
            'seatamt',
            'boat_type',
            'price',
            'child_price',
            'infant_price',
            'icon_set',
            'type',
            'master_from',
            'master_to',
            'info_from',
            'info_to',
            'text_1',
            'text_2'
        ]));
        session()->flash('success', __('messages.updated'));

        return redirect()->route('subRoute.edit', ['subRoute' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subRoute = SubRoute::find($id);
        $routeId = $subRoute->route_id;

        $activeBookingCount = BookingSubRoute::where('sub_route_id', $id)->count();

        if ($activeBookingCount > 0) {
            $subRoute->is_deleted = 'Y';
            $subRoute->deleted_at = now();
            $subRoute->update();
            session()->flash('success', __('messages.deleted'));
        } else {
            $subRoute->delete();
            session()->flash('success', __('messages.deleted'));
        }


        return redirect()->route('route.edit', ['route' => $routeId]);
    }

    public function changeStatus(Request $request, $id)
    {
        // $id = $request->id;
        $subRoute = SubRoute::where('id', $id)->first();

        if ($subRoute) {
            $subRoute->isactive = $subRoute->isactive == 'Y' ? 'N' : 'Y';
            $subRoute->save();

            if ($subRoute->isactive == 'Y') {
                Route::where('id', $subRoute->route_id)->update(['isactive' => 'Y']);
            }

            session()->flash('success', __('messages.updated'));
        }
        return redirect()->back();
    }


    public function priceStrategy(Request $request, $id)
    {
        $subRoute = SubRoute::whereId($id)->with(['priceStrategy'])->first();

        $priceStrategy = $subRoute->priceStrategy;
        if (empty($priceStrategy)) {

            //make new setting

            //check create by manual or load from price
            if (empty($request->master_price_strategy_id)) {
                //manual
                $priceStrategy = PriceStrategy::create($request->all());
                if ($priceStrategy) {
                    $subRoute->price_strategy_id = $priceStrategy->id;
                    $subRoute->save();
                    session()->flash('success', __('messages.saved'));
                    return redirect()->back();
                } else {
                    session()->flash('error', __('messages.error'));
                    return redirect()->back();
                }
            } else {
                $masterPriceStrategy = PriceStrategy::where('id', $request->master_price_strategy_id)->with('lines')->first();

                $priceStrategy = PriceStrategy::create([
                    'calculate_type' => $masterPriceStrategy->calculate_type,
                    'method' => $masterPriceStrategy->method,
                ]);

                $subRoute->price_strategy_id = $priceStrategy->id;
                $subRoute->save();

                foreach ($masterPriceStrategy->lines as $line) {
                    PriceStrategyLine::create([
                        'price_strategy_id' => $priceStrategy->id,
                        'unit' => $line->unit,
                        'condition' => $line->condition,
                        'price' => $line->price,
                        'child_price' => $line->child_price,
                        'infant_price' => $line->infant_price,
                    ]);
                }

                session()->flash('success', __('messages.updated'));
                return redirect()->back();
            }
        } else {
            if (empty($request->master_price_strategy_id)) {
                $_calculate_type = $request->calculate_type;
                $_method = $request->method;

                if ($_calculate_type == $priceStrategy->calculate_type && $_method == $priceStrategy->method) {
                    session()->flash('success', __('messages.updated'));
                    return redirect()->back();
                } else {
                    PriceStrategyLine::where('price_strategy_id', $priceStrategy->id)->delete();
                    $priceStrategy->calculate_type = $_calculate_type;
                    $priceStrategy->method = $_method;
                    $priceStrategy->save();
                    session()->flash('success', __('messages.updated'));
                    return redirect()->back();
                }
            } else {
                $masterPriceStrategy = PriceStrategy::where('id', $request->master_price_strategy_id)->with('lines')->first();
                PriceStrategyLine::where('price_strategy_id', $priceStrategy->id)->delete();
                $priceStrategy->calculate_type = $masterPriceStrategy->calculate_type;
                $priceStrategy->method = $masterPriceStrategy->method;
                $priceStrategy->save();

                foreach ($masterPriceStrategy->lines as $line) {
                    PriceStrategyLine::create([
                        'price_strategy_id' => $priceStrategy->id,
                        'unit' => $line->unit,
                        'condition' => $line->condition,
                        'price' => $line->price,
                        'child_price' => $line->child_price,
                        'infant_price' => $line->infant_price,
                    ]);
                }

                session()->flash('success', __('messages.updated'));
                return redirect()->back();
            }
        }
    }
}
