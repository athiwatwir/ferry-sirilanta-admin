<?php

namespace App\Http\Controllers;

use App\Models\AgentAccountTransection;
use App\Models\SalesPartner;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agents = SalesPartner::with(['user', 'agentAccount'])->where('type', 'agent')->where('agent_id', env('AGENT_ID'))->get();

        $accountIds = $agents->pluck('agentAccount.id')->filter()->values();
        $pendingCounts = $accountIds->isNotEmpty()
            ? AgentAccountTransection::where('type', 'topup')
            ->where('isapproved', 'N')
            ->whereIn('agent_account_id', $accountIds)
            ->selectRaw('agent_account_id, count(*) as cnt')
            ->groupBy('agent_account_id')
            ->pluck('cnt', 'agent_account_id')
            : collect();

        $pendingTopUpTotal = $pendingCounts->sum();
        foreach ($agents as $agent) {
            $agent->pending_topup_count = $agent->agentAccount
                ? (int) ($pendingCounts[$agent->agentAccount->id] ?? 0)
                : 0;
        }

        return view('pages.agent.index', [
            'title' => 'Agent',
            'agents' => $agents,
            'pendingTopUpTotal' => $pendingTopUpTotal,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.agent.create', [
            'title' => 'Create Agent'
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
        $agent = SalesPartner::with(['agentAccount.transections' => fn($q) => $q->orderBy('created_at', 'desc')], 'user')->find($id);

        $transactions = $agent->agentAccount
            ? $agent->agentAccount->transections->where('type', 'topup')
            : collect();

        return view('pages.agent.show', [
            'title' => 'Agent > ' . $agent->name,
            'breadcrumbs' => [
                'All Agent' => route('agent.index'),
                'View' => ''
            ],
            'agent' => $agent,
            'transactions' => $transactions,
        ]);
    }

    /**
     * อนุมัติรายการเติมเงิน และอัพเดท wallet_balance ใน AgentAccount
     */
    public function approveTopUp(string $agent, string $transaction)
    {
        $agentModel = SalesPartner::with('agentAccount')->findOrFail($agent);
        if (!$agentModel->agentAccount) {
            return redirect()->route('agent.show', $agent)->with('error', 'ไม่พบบัญชี Agent');
        }

        $tx = AgentAccountTransection::where('id', $transaction)
            ->where('agent_account_id', $agentModel->agentAccount->id)
            ->where('type', 'topup')
            ->firstOrFail();

        if (($tx->isapproved ?? '') === 'Y') {
            return redirect()->route('agent.show', $agent)->with('warning', 'รายการนี้อนุมัติแล้ว');
        }

        $tx->update(['isapproved' => 'Y']);

        $account = $agentModel->agentAccount;
        $account->wallet_balance = ($account->wallet_balance ?? 0) + $tx->amount;
        $account->save();

        return redirect()->route('agent.show', $agent)->with('success', 'อนุมัติเติมเงินเรียบร้อย และอัพเดทยอด wallet แล้ว');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $agent = SalesPartner::with('user')->findOrFail($id);

        return view('pages.agent.edit', [
            'title' => 'Agent > ' . $agent->name,
            'breadcrumbs' => [
                'All Agent' => route('agent.index'),
                'Edit' => ''
            ],
            'agent' => $agent,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $agent = SalesPartner::with('user')->findOrFail($id);

        $userId = $agent->user?->id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'discount' => 'nullable|numeric|min:0|max:100',
            'password' => 'nullable|string|min:8',
        ]);

        $agent->update([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'discount' => $validated['discount'] ?? $agent->discount,
        ]);

        if ($agent->user) {
            $userData = ['email' => $validated['email']];
            if (!empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }
            $agent->user->update($userData);
        }

        return redirect()->route('agent.show', $agent)->with('success', 'อัปเดตข้อมูล Agent เรียบร้อย');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
