<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletsController extends Controller
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
        //
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
        $request->validate([
            'use_over_limit_type' => 'required|string',
            'use_limit' => 'required|numeric',
            'use_over_limit' => 'required|numeric',
        ]);

        $wallet = Wallet::with('agents')->whereId($id)->first();
        $wallet->update($request->all());

        $agent = $wallet->agents[0];
        if ($wallet) {

            session()->flash('success', __('messages.updated'));
        }

        return redirect()->route('agent.wallet', ['agent' => $agent]);
    }

    public function addBalance(Request $request, string $id)
    {
        $request->validate([
            'balance' => 'required|numeric',
        ]);

        $wallet = Wallet::with('agents')->whereId($id)->first();
        $currentBalance = $wallet->balance;

        $wallet->balance = $currentBalance + $request->balance;
        $wallet->update();

        $agent = $wallet->agents[0];

        if ($wallet) {
            session()->flash('success', __('messages.updated'));
        }
        return redirect()->route('agent.wallet', ['agent' => $agent]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
