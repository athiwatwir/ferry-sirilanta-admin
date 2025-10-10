<?php

namespace App\Http\Controllers;

use App\Models\PriceStrategyLine;
use Illuminate\Http\Request;

class PriceStrategyLineController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'price_strategy_id' => 'required|string',
            'condition' => 'required|string',
            'unit' => 'required|integer',
            'price' => 'required|numeric',
            'child_price' => 'required|numeric',
            'infant_price' => 'required|numeric',
        ]);

        $line = PriceStrategyLine::create($request->all());
        if ($line) {
            session()->flash('success', __('messages.saved'));
        }

        return redirect()->back();
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
        $line = PriceStrategyLine::whereId($id)->first();
        PriceStrategyLine::whereId($id)->delete();
        session()->flash('success', __('messages.deleted'));
        return redirect()->back();
    }
}
