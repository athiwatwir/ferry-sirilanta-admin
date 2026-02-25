<?php

namespace App\Http\Controllers;

use App\Models\AgentAccount;
use App\Models\AgentAccountTransection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgentAccountController extends Controller
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
    public function show(AgentAccount $agentAccount)
    {
        $agentAccount->load(['transections' => fn ($q) => $q->orderBy('created_at', 'desc'), 'salesPartner']);

        return view('pages.agent-account.show', [
            'title' => 'Wallet',
            'agentAccount' => $agentAccount,
            'transactions' => $agentAccount->transections,
        ]);
    }

    /**
     * เติมเงิน (Top up) - บันทึกสลิป จำนวนเงิน และเพิ่มรายการใน AgentAccountTransection
     */
    public function topUp(Request $request, AgentAccount $agentAccount)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'slip' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'slip.required' => 'กรุณาแนบสลิปการโอน',
            'amount.required' => 'กรุณาระบุจำนวนเงิน',
        ]);

        $imagePath = null;
        if ($request->hasFile('slip')) {
            $file = $request->file('slip');
            $filename = 'topup_' . uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('agent_account_slips', $filename, 'public');
            $imagePath = 'storage/' . $path;
        }

        AgentAccountTransection::create([
            'agent_account_id' => $agentAccount->id,
            'type' => 'topup',
            'amount' => $request->amount,
            'description' => $request->description,
            'image_path' => $imagePath,
            'isapproved' => 'N',
        ]);

        session()->flash('success', 'บันทึกคำขอเติมเงินเรียบร้อย รอการอนุมัติ');

        return redirect()->route('agentAccount.show', $agentAccount);
    }

    /**
     * แสดงไฟล์สลิป (ให้ดูได้แม้ไม่มี symlink public/storage)
     */
    public function showSlip(AgentAccountTransection $transaction)
    {
        if (!$transaction->image_path) {
            abort(404);
        }
        $relativePath = str_replace('storage/', '', $transaction->image_path);
        $fullPath = Storage::disk('public')->path($relativePath);
        if (!is_file($fullPath)) {
            abort(404);
        }
        return response()->file($fullPath, [
            'Content-Disposition' => 'inline',
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
