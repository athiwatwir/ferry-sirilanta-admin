<?php

namespace App\Http\Controllers\Payment;

use App\Exceptions\TwoC2PException;
use App\Http\Controllers\Controller;
use App\Models\AgentAccountTransection;
use App\Services\TwoC2PService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TwoC2PController extends Controller
{
    public function __construct(
        private TwoC2PService $twoC2P
    ) {}

    /**
     * Frontend return URL after customer finishes / cancels payment page.
     */
    public function frontend(Request $request)
    {
        $rawPayload = $request->input('paymentResponse')
            ?? $request->input('payload')
            ?? $request->getContent();

        $frontendRespCode = null;

        try {
            $payload = $this->twoC2P->decodeRequestPayload(
                is_string($rawPayload) ? $rawPayload : null
            );
            $frontendRespCode = (string) ($payload['respCode'] ?? '');
            $payload = $this->twoC2P->resolveCompletedPayment(
                $payload,
                $this->resolvePreferredProfile($payload)
            );
        } catch (TwoC2PException $e) {
            Log::warning('2C2P frontend decode failed', [
                'message' => $e->getMessage(),
                'payload_preview' => is_string($rawPayload) ? substr($rawPayload, 0, 200) : null,
                'request_keys' => array_keys($request->all()),
            ]);

            return $this->browserReturn(
                route('booking.index', [
                    'payment' => 'error',
                    'payment_msg' => 'ไม่สามารถตรวจสอบผลการชำระเงินได้',
                ])
            );
        }

        $invoiceNo = (string) ($payload['invoiceNo'] ?? '');
        $respCode = (string) ($payload['respCode'] ?? '');
        $success = $this->twoC2P->isPaymentSuccessful($respCode)
            || !empty($payload['approvedViaFrontend2000'])
            || $frontendRespCode === '2000';

        $cancelled = in_array($respCode, ['0003', '2003', '0014'], true)
            || in_array((string) $frontendRespCode, ['0003', '2003', '0014'], true)
            || str_contains(strtolower((string) ($payload['respDesc'] ?? '')), 'cancel');

        if ($cancelled) {
            $success = false;
        }

        $message = $success
            ? 'ชำระเงินสำเร็จ Wallet ถูกเติมแล้ว'
            : ($cancelled
                ? 'ยกเลิกการชำระเงินแล้ว'
                : ('ชำระเงินไม่สำเร็จ: ' . ($payload['respDesc'] ?? $respCode ?: 'Unknown')));

        $status = $success ? 'success' : ($cancelled ? 'cancelled' : 'error');

        Log::info('2C2P frontend callback', [
            'invoiceNo' => $invoiceNo,
            'frontendRespCode' => $frontendRespCode,
            'resolvedRespCode' => $respCode,
            'success' => $success,
            'userDefined1' => $payload['userDefined1'] ?? null,
            'userDefined2' => $payload['userDefined2'] ?? null,
            'userDefined3' => $payload['userDefined3'] ?? null,
            'userDefined4' => $payload['userDefined4'] ?? null,
            'userDefined5' => $payload['userDefined5'] ?? null,
        ]);

        $transaction = $this->findWalletTopUpTransaction($invoiceNo, $payload);
        if ($transaction) {
            if ($success) {
                $this->approveWalletTopUp($transaction, $payload);
                $message = 'ชำระเงินสำเร็จ Wallet ถูกเติมแล้ว';
                $status = 'success';
            }

            return $this->browserReturn($this->walletReturnUrl($transaction, $payload, $status, $message));
        }

        // Wallet top-up ที่หา transaction ไม่เจอ — ยังพยายามหาจาก invoice / account อีกครั้ง
        if ($this->looksLikeWalletTopUp($invoiceNo, $payload)) {
            Log::warning('2C2P wallet top-up transaction not found on frontend', [
                'invoiceNo' => $invoiceNo,
                'payload' => $payload,
            ]);
        }

        return $this->browserReturn(
            route('booking.index', [
                'payment' => $status,
                'payment_msg' => $success ? 'ชำระเงินสำเร็จ' : $message,
            ])
        );
    }

    /**
     * Backend notification URL from 2C2P (server-to-server).
     */
    public function backend(Request $request)
    {
        $rawPayload = $request->input('paymentResponse')
            ?? $request->input('payload')
            ?? $request->getContent();

        try {
            $payload = $this->twoC2P->decodeRequestPayload(
                is_string($rawPayload) ? $rawPayload : null
            );
            $frontendRespCode = (string) ($payload['respCode'] ?? '');
            $payload = $this->twoC2P->resolveCompletedPayment(
                $payload,
                $this->resolvePreferredProfile($payload)
            );
        } catch (TwoC2PException $e) {
            Log::warning('2C2P backend decode failed', ['message' => $e->getMessage()]);
            return response('ERROR', 400);
        }

        $success = $this->twoC2P->isPaymentSuccessful($payload['respCode'] ?? null)
            || !empty($payload['approvedViaFrontend2000'])
            || (($frontendRespCode ?? '') === '2000');

        Log::info('2C2P backend notification', [
            'invoiceNo' => $payload['invoiceNo'] ?? null,
            'respCode' => $payload['respCode'] ?? null,
            'frontendRespCode' => $frontendRespCode ?? null,
            'success' => $success,
        ]);

        if ($success) {
            $transaction = $this->findWalletTopUpTransaction(
                (string) ($payload['invoiceNo'] ?? ''),
                $payload
            );
            if ($transaction) {
                $this->approveWalletTopUp($transaction, $payload);
            }
        }

        return response('OK', 200);
    }

    private function browserReturn(string $url)
    {
        return response()
            ->view('payment.2c2p-return', ['redirectUrl' => $url])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    private function resolvePreferredProfile(array $payload): ?string
    {
        $profile = strtolower(trim((string) ($payload['userDefined4'] ?? '')));
        if (in_array($profile, [TwoC2PService::PROFILE_CREDIT, TwoC2PService::PROFILE_ETC], true)) {
            return $profile;
        }

        return null;
    }

    private function looksLikeWalletTopUp(string $invoiceNo, array $payload): bool
    {
        return str_starts_with(strtoupper($invoiceNo), 'WT')
            || ($payload['userDefined1'] ?? '') === 'wallet_topup'
            || str_contains((string) ($payload['description'] ?? ''), 'Wallet Top Up');
    }

    private function walletReturnUrl(
        AgentAccountTransection $transaction,
        array $payload,
        string $status,
        string $message
    ): string {
        $returnEmbed = (($payload['userDefined5'] ?? '') === 'embed')
            || (($payload['userDefined4'] ?? '') === 'embed');

        if ($returnEmbed) {
            return route('agentAccount.topUpPage', [
                'agentAccount' => $transaction->agent_account_id,
                'embed' => 1,
                'payment' => $status,
                'payment_msg' => $message,
            ]);
        }

        return route('agentAccount.show', [
            'agentAccount' => $transaction->agent_account_id,
            'payment' => $status,
            'payment_msg' => $message,
        ]);
    }

    private function findWalletTopUpTransaction(string $invoiceNo, array $payload = []): ?AgentAccountTransection
    {
        $candidates = [];

        $userDefined3 = trim((string) ($payload['userDefined3'] ?? ''));
        if ($userDefined3 !== '') {
            $candidates[] = $userDefined3;
        }

        $invoiceNo = strtoupper(trim($invoiceNo));
        if ($invoiceNo !== '' && str_starts_with($invoiceNo, 'WT')) {
            $hex = strtolower(substr($invoiceNo, 2));
            $hex = preg_replace('/[^a-f0-9]/', '', $hex) ?? '';
            if (strlen($hex) >= 32) {
                $hex = substr($hex, 0, 32);
                $candidates[] = substr($hex, 0, 8) . '-'
                    . substr($hex, 8, 4) . '-'
                    . substr($hex, 12, 4) . '-'
                    . substr($hex, 16, 4) . '-'
                    . substr($hex, 20, 12);
            }
            $candidates[] = $invoiceNo;
        }

        foreach (array_unique(array_filter($candidates)) as $id) {
            $found = AgentAccountTransection::with('agentAccount')
                ->where('id', $id)
                ->where('type', 'topup')
                ->first();
            if ($found) {
                return $found;
            }
        }

        if ($invoiceNo !== '') {
            $byDesc = AgentAccountTransection::with('agentAccount')
                ->where('type', 'topup')
                ->where('description', 'like', '%' . $invoiceNo . '%')
                ->orderByDesc('created_at')
                ->first();
            if ($byDesc) {
                return $byDesc;
            }
        }

        // Fallback: หา topup ล่าสุดของบัญชีที่ยังไม่อนุมัติ
        $accountId = trim((string) ($payload['userDefined2'] ?? ''));
        if ($accountId !== '') {
            $pending = AgentAccountTransection::with('agentAccount')
                ->where('agent_account_id', $accountId)
                ->where('type', 'topup')
                ->where('isapproved', 'N')
                ->where(function ($q) {
                    $q->where('description', 'like', '[CARD]%')
                        ->orWhere('description', 'like', '[ETC]%');
                })
                ->orderByDesc('created_at')
                ->first();
            if ($pending) {
                return $pending;
            }
        }

        return null;
    }

    private function approveWalletTopUp(AgentAccountTransection $transaction, array $payload): void
    {
        if (($transaction->isapproved ?? '') === 'Y') {
            return;
        }

        DB::transaction(function () use ($transaction, $payload) {
            $locked = AgentAccountTransection::with('agentAccount')
                ->where('id', $transaction->id)
                ->lockForUpdate()
                ->first();

            if (!$locked || ($locked->isapproved ?? '') === 'Y') {
                return;
            }

            $ref = $payload['tranRef'] ?? $payload['referenceNo'] ?? null;
            $desc = (string) ($locked->description ?? '');
            if ($ref && !str_contains($desc, (string) $ref)) {
                $desc = trim($desc . ' REF:' . $ref);
            }

            $locked->update([
                'isapproved' => 'Y',
                'description' => $desc !== '' ? $desc : $locked->description,
            ]);

            if ($locked->agentAccount) {
                $account = \App\Models\AgentAccount::where('id', $locked->agent_account_id)
                    ->lockForUpdate()
                    ->first();
                if ($account) {
                    $account->wallet_balance = ((float) ($account->wallet_balance ?? 0)) + (float) $locked->amount;
                    $account->save();
                    $locked->setRelation('agentAccount', $account);
                }
            }

            Log::info('2C2P wallet top-up approved', [
                'transaction_id' => $locked->id,
                'amount' => $locked->amount,
                'invoiceNo' => $payload['invoiceNo'] ?? null,
                'wallet_balance' => $locked->agentAccount->wallet_balance ?? null,
            ]);
        });
    }
}
