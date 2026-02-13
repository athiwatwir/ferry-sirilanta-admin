<?php

namespace App\Http\Controllers;

use App\Models\SalesPartner;
use Illuminate\Http\Request;

class BrokerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brokers = SalesPartner::with('user')->where('type', 'broker')->where('agent_id', env('AGENT_ID'))->get();
        return view('pages.broker.index', [
            'title' => 'Broker',
            'brokers' => $brokers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('pages.broker.create', [
            'title' => 'Create Broker'
        ]);
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
        $broker = SalesPartner::with('user', 'agentAccount')->find($id);
        return view('pages.broker.show', [
            'title' => 'Broker > ' . $broker->name,
            'broker' => $broker,
            'breadcrumbs' => [
                'All Broker' => route('broker.index'),
                'View' => ''
            ],
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
}
