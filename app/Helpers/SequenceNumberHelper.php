<?php

namespace App\Helpers;

use App\Models\Sequencenumber;


//https://medium.com/@icecslox/laravel-5-4-%E0%B8%AA%E0%B8%A3%E0%B9%89%E0%B8%B2%E0%B8%87-helper-%E0%B9%84%E0%B8%A7%E0%B9%89%E0%B9%83%E0%B8%8A%E0%B9%89%E0%B8%87%E0%B8%B2%E0%B8%99-52f93ee1805a
class SequenceNumberHelper
{
    public static function getDocumentno($type, $agentId = null)
    {
        $sequence = Sequencenumber::where("type", $type)
            ->when($type != 'booking', function ($query) use ($agentId) {
                return $query->where('agent_id', $agentId);
            })
            ->lockForUpdate()
            ->first();

        // ถ้าไม่เจอ return ทันที
        if (!$sequence) {
            return '0000000000';
        }

        $today = now()->format('Y-m-d');
        $lastResetDate = $sequence->updated_at->format('Y-m-d');

        // Reset running number if it's a new day
        if ($today != $lastResetDate) {
            $sequence->running = 0;
        }

        // Build document number
        $prefix = $sequence->prefix;

        if (!empty($sequence->dateformat)) {
            $prefix .= now()->format($sequence->dateformat);
        }

        $nextNumber = $sequence->running + 1;
        $newSequenceNumber = $prefix . str_pad(
            $nextNumber,
            $sequence->running_digit,
            "0",
            STR_PAD_LEFT
        );

        // Update sequence
        $sequence->running = $nextNumber;
        $sequence->save();

        return $newSequenceNumber;
    }
}
