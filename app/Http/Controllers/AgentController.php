<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Sequencenumber;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $agents = Agent::where('type', 'AG')->where('parent_agent_id', env("AGENT_ID"))->get();

        $brokers = Agent::where('type', 'BK')->where('parent_agent_id', env("AGENT_ID"))->get();

        return view('pages.agent.index', [
            'title' => 'Broker User/Agent Management',
            'agents' => $agents,
            'brokers' => $brokers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $agentApi = Agent::whereId(env('AGENT_ID'))->first();
        $type = request()->type;


        return view('pages.agent.create', [
            'title' => 'Create New',
            'breadcrumbs' => [
                'All Broker User/Agent' => route('agent.index'),
                'Create Agent' => ''
            ],
            'agentApi' => $agentApi,
            'type' => $type
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'code' => 'required|string',

            'email' => 'required|email',
            'password' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // สูงสุด 2MB
        ]);

        $agent = Agent::create($request->all());


        if (!$agent) {

            session()->flash('error', __('messages.error'));
            return redirect()->back();
        }

        //create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'agent_id' => $agent->id,
            'role' => $request->type,
        ]);

        //make wallet
        $wallet = Wallet::create([
            'balance' => 0
        ]);
        $agent->wallet_id = $wallet->id;

        $agent->update();

        if (!empty($request->logo)) {
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();

            // ตั้งชื่อไฟล์แบบไม่ซ้ำ เช่น ใช้ timestamp + agent code
            $filename = $agent->code . '_' . time() . '.' . $extension;

            // หรือใช้ uniqid()
            // $filename = $agent->code . '_' . uniqid() . '.' . $extension;

            $path = $file->storeAs('agents', $filename, 'public');
            $agent->logo = 'storage/' . $path;
            $agent->save();
        } else {
            $agent->logo = 'images/agent.png';
            $agent->save();
        }

        $this->clearCache();

        return redirect()->route('agent.show', ['agent' => $agent]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $agent = Agent::whereId($id)->first();

        $title = 'View Agent ' . $agent->name;
        if ($agent->type == 'BK') {
            $title = 'View Broker ' . $agent->name;
        }

        return view('pages.agent.show', [
            'title' => $title,
            'agent' => $agent,
            'breadcrumbs' => [
                'All agents' => route('agent.index'),
                'View Agent' . $agent->name => ''
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $agent = Agent::whereId($id)->first();
        $ticketSeq = Sequencenumber::where('type', 'ticket')->where('agent_id', $agent->id)->first();

        $title = 'Edit Agent ' . $agent->name;
        if ($agent->type == 'BK') {
            $title = 'Edit Broker ' . $agent->name;
        }

        return view('pages.agent.edit', [
            'title' => $title,
            'agent' => $agent,
            'ticketSeq' => $ticketSeq,
            'breadcrumbs' => [
                'All agents' => route('agent.index'),
                'Edit Agent' => ''
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //dd($request);
        $request->validate([
            'name' => 'required|string',

        ]);

        $agent = Agent::whereId($id)->first();
        $agent->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_use_wallet' => $request->is_use_wallet ?? 'N',
        ]);

        if (!empty($request->logo)) {
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();

            // ตั้งชื่อไฟล์แบบไม่ซ้ำ เช่น ใช้ timestamp + agent code
            $filename = $agent->code . '_' . time() . '.' . $extension;

            // หรือใช้ uniqid()
            // $filename = $agent->code . '_' . uniqid() . '.' . $extension;

            $path = $file->storeAs('agents', $filename, 'public');
            $agent->logo = 'storage/' . $path;
            $agent->save();
        }

        //Ticket prefix
        if ($request->prefix_old != $request->prefix) {
            $ticketSeq = Sequencenumber::where('type', 'ticket')->where('agent_id', $agent->id)->first();
            $ticketSeq->prefix = $request->prefix;
            $ticketSeq->save();
        }


        $this->clearCache();

        return redirect()->route('agent.show', ['agent' => $agent]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Agent::whereId($id)->delete();
        session()->flash('success', __('messages.deleted'));
        return redirect()->route('agent.index');
    }

    public function route($id)
    {
        $agent = Agent::whereId($id)->with(['agentSubRoutes'])->first();

        // dd($agent);
        return view('pages.agent.route', [
            'title' => 'View Agent ' . $agent->name,
            'agent' => $agent,
            'breadcrumbs' => [
                'All agents' => route('agent.index'),
                'View Agent' . $agent->name => ''
            ]
        ]);
    }

    public function wallet($id)
    {

        $agent = Agent::with(['wallet'])->whereId($id)->first();
        //dd($agent);
        return view('pages.agent.wallet', [
            'title' => 'View Agent ' . $agent->name,
            'agent' => $agent,
            'breadcrumbs' => [
                'All agents' => route('agent.index'),
                'View Agent' . $agent->name => ''
            ]
        ]);
    }

    public function user($id)
    {
        $agent = Agent::whereId($id)->first();
        $users = User::where('agent_id', $agent->id)->get();

        return view('pages.agent.user', [
            'title' => 'User in Agent ' . $agent->name,
            'agent' => $agent,
            'users' => $users,
            'breadcrumbs' => [
                'All agents' => route('agent.index'),
                'User in Agent ' . $agent->name => ''
            ]
        ]);
    }

    private function clearCache()
    {
        Cache::forget('active_api_agents');
        //Cache::forget('dest_station_service');
    }
}
