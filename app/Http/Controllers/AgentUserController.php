<?php

namespace App\Http\Controllers;

use App\Models\AgentUser;
use Illuminate\Http\Request;

class AgentUserController extends Controller
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
        $agentId = $request->agent_id;
        $agentUser = AgentUser::create($request->all());
        session()->flash('success', __('messages.saved'));
        return redirect()->route('agent.user', ['agent' => $agentId]);
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
        $agentUser = AgentUser::whereId($id)->first();
        $agentId = $agentUser->agent_id;

        $agentUser->delete();

        session()->flash('success', __('messages.deleted'));
        return redirect()->route('agent.user', ['agent' => $agentId]);
    }
}
