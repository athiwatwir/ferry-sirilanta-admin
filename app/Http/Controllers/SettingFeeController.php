<?php

namespace App\Http\Controllers;

use App\Models\SettingFee;
use Illuminate\Http\Request;

class SettingFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agentId = env('AGENT_ID');
        $settingFee = SettingFee::where('agent_id', $agentId)->first();
        if (empty($settingFee)) {
            $settingFee = SettingFee::create([
                'agent_id' => $agentId
            ]);
        }

        return redirect()->route('settingFee.edit', ['settingFee' => $settingFee]);
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

        return view('pages.setting-fee.edit');
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
}
