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
     *
     * หมายเหตุ: 2C2P มัก POST กลับแบบ cross-site ทำให้ session cookie ไม่มาด้วย
     * จึงไม่พึ่ง session flash — ใส่สถานะใน query แล้วใช้ HTML bridge เป็น top-level GET
     * เพื่อให้ browser ส่ง cookie เดิมกลับมา (ดู PreserveSessionOnCrossSiteReturn)
     *
     * Frontend มักส่ง paymentResponse เป็น Base64 JSON + respCode=2000
     * ต้อง Payment Inquiry เพื่อยืนยัน 0000 แล้วค่อยอนุมัติ wallet
     */
    public function frontend(Request $request)
    {
        $rawPayload = $request->input('paymentResponse')
            ?? $request->input('payload')
            ?? $request->getContent();

        try {
            $payload = $this->twoC2P->decodeRequestPayload(
                is_string($rawPayload) ? $rawPayload : null
            );
            $payload = $this->twoC2P->resolveCompletedPayment(
                $payload,
                isset($payload['userDefined4']) ? (string) $payload['userDefined4'] : null
            );
        } catch (TwoC2PException $e) {
            Log::warning('2C2P frontend decode failed', [
                'message' => $e->getMessage(),
                'payload_preview' => is_string($rawPayload) ? substr($rawPayload, 0, 120) : null,
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
        $success = $this->twoC2P->isPaymentSuccessful($respCode);
        $cancelled = in_array($respCode, ['0003', '2003', '0014'], true)
            || str_contains(strtolower((string) ($payload['respDesc'] ?? '')), 'cancel');

        $message = $success
            ? 'ชำระเงินสำเร็จ Wallet ถูกเติมแล้ว'
            : ($cancelled
                ? 'ยกเลิกการชำระเงินแล้ว'
                : ('ชำระเงินไม่สำเร็จ: ' . ($payload['respDesc'] ?? $respCode ?: 'Unknown')));

        $status = $success ? 'success' : ($cancelled ? 'cancelled' : 'error');

        $transaction = $this->findWalletTopUpTransaction($invoiceNo, $payload);
        if ($transaction) {
            if ($success) {
                $this->approveWalletTopUp($transaction, $payload);
                $message = 'ชำระเงินสำเร็จ Wallet ถูกเติมแล้ว';
                $status = 'success';
            }

            return $this->browserReturn(
                route('agentAccount.show', [
                    'agentAccount' => $transaction->agent_account_id,
                    'payment' => $status,
                    'payment_msg' => $message,
                ])
            );
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
            $payload = $this->twoC2P->resolveCompletedPayment(
                $payload,
                isset($payload['userDefined4']) ? (string) $payload['userDefined4'] : null
            );
        } catch (TwoC2PException $e) {
            Log::warning('2C2P backend decode failed', ['message' => $e->getMessage()]);
            return response('ERROR', 400);
        }

        Log::info('2C2P backend notification', [
            'invoiceNo' => $payload['invoiceNo'] ?? null,
            'respCode' => $payload['respCode'] ?? null,
        ]);

        if ($this->twoC2P->isPaymentSuccessful($payload['respCode'] ?? null)) {
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

    /**
     * คืนหน้า bridge แล้วพาไปปลายทางด้วย top-level GET (รักษา session cookie)
     */
    private function browserReturn(string $url)
    {
        return response()
            ->view('payment.2c2p-return', ['redirectUrl' => $url])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }

    private function findWalletTopUpTransaction(string $invoiceNo, array $payload = []): ?AgentAccountTransection
    {
        // จาก userDefined3 ที่ส่งตอน create token (= transaction uuid)
        $userDefined3 = trim((string) ($payload['userDefined3'] ?? ''));
        if ($userDefined3 !== '') {
            $byUserDefined = AgentAccountTransection::with('agentAccount')
                ->where('id', $userDefined3)
                ->where('type', 'topup')
                ->first();
            if ($byUserDefined) {
                return $byUserDefined;
            }
        }

        if ($invoiceNo === '') {
            return null;
        }

        // Invoice format from wallet top-up: WT + uuid without dashes
        if (str_starts_with(strtoupper($invoiceNo), 'WT') && strlen($invoiceNo) >= 34) {
            $hex = strtolower(substr($invoiceNo, 2));
            $uuid = substr($hex, 0, 8) . '-'
                . substr($hex, 8, 4) . '-'
                . substr($hex, 12, 4) . '-'
                . substr($hex, 16, 4) . '-'
                . substr($hex, 20, 12);

            $byInvoice = AgentAccountTransection::with('agentAccount')
                ->where('id', $uuid)
                ->where('type', 'topup')
                ->first();
            if ($byInvoice) {
                return $byInvoice;
            }
        }

        return AgentAccountTransection::with('agentAccount')
            ->where('id', $invoiceNo)
            ->where('type', 'topup')
            ->first();
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
                $account = $locked->agentAccount;
                $account->wallet_balance = ((float) ($account->wallet_balance ?? 0)) + (float) $locked->amount;
                $account->save();
            }

            Log::info('2C2P wallet top-up approved', [
                'transaction_id' => $locked->id,
                'amount' => $locked->amount,
                'invoiceNo' => $payload['invoiceNo'] ?? null,
            ]);
        });
    }
}
