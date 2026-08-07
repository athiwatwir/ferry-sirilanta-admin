<?php

namespace App\Http\Controllers;

use App\Models\AgentAccount;
use App\Models\AgentAccountTransection;
use App\Exceptions\TwoC2PException;
use App\Services\TwoC2PService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    public function show(Request $request, AgentAccount $agentAccount)
    {
        // จาก 2C2P frontend return (query) → flash แล้วตัด query ออก
        if ($request->filled('payment')) {
            $status = (string) $request->input('payment');
            $message = trim((string) $request->input('payment_msg', ''));
            if ($message === '') {
                $message = match ($status) {
                    'success' => 'ชำระเงินสำเร็จ',
                    'cancelled' => 'ยกเลิกการชำระเงินแล้ว',
                    default => 'ชำระเงินไม่สำเร็จ',
                };
            }

            $flashKey = $status === 'success' ? 'success' : ($status === 'cancelled' ? 'warning' : 'error');

            return redirect()
                ->route('agentAccount.show', $agentAccount)
                ->with($flashKey, $message);
        }

        $agentAccount->load(['transections' => fn($q) => $q->orderBy('created_at', 'desc'), 'salesPartner']);

        return view('pages.agent-account.show', [
            'title' => 'Wallet',
            'agentAccount' => $agentAccount,
            'transactions' => $agentAccount->transections,
        ]);
    }

    /**
     * หน้า Top up แยกสำหรับฝัง iframe / เปิดใช้งานจากหน้าอื่น
     * URL: /agent-account/{agentAccount}/top-up?embed=1
     */
    public function topUpPage(Request $request, AgentAccount $agentAccount)
    {
        if ($request->filled('payment')) {
            $status = (string) $request->input('payment');
            $message = trim((string) $request->input('payment_msg', ''));
            if ($message === '') {
                $message = match ($status) {
                    'success' => 'ชำระเงินสำเร็จ',
                    'cancelled' => 'ยกเลิกการชำระเงินแล้ว',
                    default => 'ชำระเงินไม่สำเร็จ',
                };
            }

            $flashKey = $status === 'success' ? 'success' : ($status === 'cancelled' ? 'warning' : 'error');

            return redirect()
                ->route('agentAccount.topUpPage', [
                    'agentAccount' => $agentAccount,
                    'embed' => $request->input('embed', 1),
                ])
                ->with($flashKey, $message);
        }

        $agentAccount->load('salesPartner');

        $amount = $request->input('amount');
        if ($amount !== null && $amount !== '' && is_numeric($amount) && (float) $amount > 0) {
            $amount = round((float) $amount, 2);
        } else {
            $amount = null;
        }

        return view('pages.agent-account.top-up', [
            'title' => 'Top up',
            'agentAccount' => $agentAccount,
            'embed' => $request->boolean('embed', true),
            'amount' => $amount,
            'method' => $request->input('method'), // transfer|card|etc (optional)
        ]);
    }

    /**
     * เติมเงิน (Top up) - โอนเงิน / บัตรเครดิต / QR-Wallet (2C2P)
     */
    public function topUp(Request $request, AgentAccount $agentAccount)
    {
        $paymentType = $request->input('payment_type', 'transfer');

        if (!in_array($paymentType, ['transfer', 'card', 'etc'], true)) {
            return redirect()
                ->to($this->topUpReturnUrl($request, $agentAccount))
                ->with('error', 'ช่องทางการชำระเงินไม่ถูกต้อง');
        }

        if (in_array($paymentType, ['card', 'etc'], true)) {
            return $this->topUpViaTwoC2P($request, $agentAccount, $paymentType);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'slip' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'slip.required' => 'กรุณาแนบสลิปการโอน',
            'amount.required' => 'กรุณาระบุจำนวนเงิน',
            'amount.min' => 'จำนวนเงินต้องมากกว่า 0',
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

        return redirect()
            ->to($this->topUpReturnUrl($request, $agentAccount))
            ->with('success', 'บันทึกคำขอเติมเงินเรียบร้อย รอการอนุมัติ');
    }

    /**
     * Top up ผ่าน 2C2P Hosted Payment Page
     */
    private function topUpViaTwoC2P(Request $request, AgentAccount $agentAccount, string $paymentType)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ], [
            'amount.required' => 'กรุณาระบุจำนวนเงิน',
            'amount.min' => 'จำนวนเงินต้องมากกว่า 0',
        ]);

        $isCard = $paymentType === 'card';
        $tag = $isCard ? '[CARD]' : '[ETC]';
        $note = trim((string) $request->input('description', ''));
        $description = $tag . ($note !== '' ? ' ' . $note : '');
        $embed = $request->boolean('embed');

        $transaction = AgentAccountTransection::create([
            'agent_account_id' => $agentAccount->id,
            'type' => 'topup',
            'amount' => $request->amount,
            'description' => $description,
            'image_path' => null,
            'isapproved' => 'N',
        ]);

        $invoiceNo = 'WT' . strtoupper(str_replace('-', '', $transaction->id));
        $transaction->update([
            'description' => trim($description . ' INV:' . $invoiceNo),
        ]);

        if ($isCard) {
            $channels = ['CC'];
            $profile = TwoC2PService::PROFILE_CREDIT;
            $label = 'บัตรเครดิต';
        } else {
            $channels = (array) config('twoc2p.etc_payment_channels', ['THQR', 'DPAY', 'QRC', 'CSQR']);
            $profile = TwoC2PService::PROFILE_ETC;
            $label = 'QR / Wallet';
        }

        try {
            $twoC2P = app(TwoC2PService::class);
            $payment = $twoC2P->createPaymentToken([
                'invoiceNo' => $invoiceNo,
                'description' => 'Wallet Top Up #' . $invoiceNo,
                'amount' => (float) $request->amount,
                'currencyCode' => config('twoc2p.currency_code', 'THB'),
                'paymentChannel' => $channels,
                'merchantProfile' => $profile,
                'frontendReturnUrl' => route('payment.2c2p.frontend'),
                'backendReturnUrl' => route('payment.2c2p.backend'),
                'userDefined1' => 'wallet_topup',
                'userDefined2' => (string) $agentAccount->id,
                'userDefined3' => (string) $transaction->id,
                'userDefined4' => $profile,
                'userDefined5' => $embed ? 'embed' : null,
            ]);

            if (!empty($payment['paymentToken'])) {
                $twoC2P->rememberPaymentToken($invoiceNo, $payment['paymentToken'], $transaction->id);
            }
        } catch (TwoC2PException $e) {
            Log::error('2C2P wallet top-up failed', [
                'transaction_id' => $transaction->id,
                'payment_type' => $paymentType,
                'message' => $e->getMessage(),
                'respCode' => $e->respCode,
            ]);

            return redirect()
                ->to($this->topUpReturnUrl($request, $agentAccount))
                ->with('error', "ไม่สามารถเปิดหน้าชำระ{$label}ได้: " . $e->getMessage());
        }

        return redirect()->away($payment['webPaymentUrl']);
    }

    private function topUpReturnUrl(Request $request, AgentAccount $agentAccount): string
    {
        if ($request->boolean('embed') || $request->input('embed') === '1') {
            return route('agentAccount.topUpPage', [
                'agentAccount' => $agentAccount,
                'embed' => 1,
            ]);
        }

        return route('agentAccount.show', $agentAccount);
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
