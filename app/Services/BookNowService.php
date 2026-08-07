<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\SalesPartner;
use App\Models\User;

class BookNowService
{
    /**
     * สร้าง URL หน้าเว็บจอง (WEB_URL) พร้อม aff + ap (agent public key)
     */
    public function buildUrl(User $user, ?SalesPartner $salesPartner = null): string
    {
        $query = ['aff' => (string) $user->id];

        $agentApi = $this->resolveAgentApi($salesPartner);
        $publicKey = $this->ensurePublicKey($agentApi);

        if (filled($publicKey)) {
            $query['ap'] = $publicKey;
        }

        $baseUrl = rtrim((string) env('WEB_URL'), '/');

        return $baseUrl . '?' . http_build_query($query);
    }

    /**
     * ใช้ agentApi จาก sales partner ก่อน แล้วค่อย fallback session
     */
    protected function resolveAgentApi(?SalesPartner $salesPartner): ?Agent
    {
        return $salesPartner?->agentApi ?? session('agentApi');
    }

    /**
     * รับประกันว่ามี public_key และบันทึกถ้ามีการสร้างใหม่
     */
    protected function ensurePublicKey(?Agent $agentApi): ?string
    {
        if (!$agentApi) {
            return null;
        }

        $agentApi->ensurePublicKey();
        if ($agentApi->isDirty('public_key')) {
            $agentApi->save();
        }

        return filled($agentApi->public_key) ? (string) $agentApi->public_key : null;
    }
}
