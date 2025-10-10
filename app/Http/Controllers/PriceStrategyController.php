<?php

namespace App\Http\Controllers;

use App\Models\PriceStrategy;
use App\Models\PriceStrategyLine;
use Illuminate\Http\Request;

class PriceStrategyController extends Controller
{
    public static $CalculateMethods = [
        'percent' => '%',
        'amount' => 'Amount/THB'
    ];

    public static $CalculateTypes = [
        'seat' => 'Seat',
        'hour' => 'Hour'
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $priceStrategies = PriceStrategy::where('ismaster', 'Y')->get();

        return view('pages.price-strategy.index', [
            'title' => 'Pricing Rule – กฎการคิดราคา',
            'calculateMethods' => PriceStrategyController::$CalculateMethods,
            'calculateTypes' => PriceStrategyController::$CalculateTypes,
            'priceStrategies' => $priceStrategies
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $priceStrategy = PriceStrategy::create($request->all());
        if ($priceStrategy) {
            session()->flash('success', __('messages.saved'));
            return redirect()->route('priceStrategy.edit', ['priceStrategy' => $priceStrategy]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $priceStrategy = PriceStrategy::whereId($id)->with(['lines'])->first();

        return view('pages.price-strategy.edit', [
            'title' => 'Edit Pricing Rule – กฎการคิดราคา',
            'priceStrategy' => $priceStrategy,
            'calculateMethods' => PriceStrategyController::$CalculateMethods,
            'calculateTypes' => PriceStrategyController::$CalculateTypes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $priceStrategy = PriceStrategy::whereId($id)->first();
        $old = $priceStrategy;

        $priceStrategy = PriceStrategy::whereId($id)->update([
            'name' => $request->name,
            'calculate_type' => $request->calculate_type,
            'method' => $request->method,
            'description' => $request->description,
        ]);
        if ($priceStrategy) {
            if ($old->method != $request->method) {
                PriceStrategyLine::where('price_strategy_id', $id)->delete();
            }

            if ($old->calculate_type != $request->calculate_type) {
                PriceStrategyLine::where('price_strategy_id', $id)->delete();
            }

            session()->flash('success', __('messages.updated'));
            // return redirect()->route('priceStrategy.edit', ['priceStrategy' => $priceStrategy]);
        }

        return redirect()->route('priceStrategy.edit', ['priceStrategy' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function changeStatus(Request $request, $id)
    {
        // $id = $request->id;
        $item = PriceStrategy::where('id', $id)->first();

        if ($item) {
            $item->isactive = $item->isactive == 'Y' ? 'N' : 'Y';
            $item->save();
            session()->flash('success', __('messages.updated'));
        }

        return redirect()->back();
    }
}
