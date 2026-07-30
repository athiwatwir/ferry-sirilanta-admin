<?php

namespace App\Http\Controllers;

use App\Models\SalesPartner;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $salesPartner = null;

        if ($user->sales_partner_id) {
            $salesPartner = SalesPartner::with('agentApi')->find($user->sales_partner_id);
        }

        $displayName = $salesPartner?->name
            ?: $user->name
            ?: 'Guest';

        $hour = (int) now()->format('H');
        if ($hour < 12) {
            $greeting = 'สวัสดีตอนเช้า';
            $greetingEn = 'Good morning';
        } elseif ($hour < 17) {
            $greeting = 'สวัสดี';
            $greetingEn = 'Hello';
        } else {
            $greeting = 'สวัสดีตอนเย็น';
            $greetingEn = 'Good evening';
        }

        $bookNowQuery = ['aff' => (string) $user->id];

        // ใช้ agentApi จาก sales partner (ไม่พึ่ง session เก่าที่อาจไม่มี public_key)
        $agentApi = $salesPartner?->agentApi ?? session('agentApi');
        if ($agentApi) {
            $agentApi->ensurePublicKey();
            if ($agentApi->isDirty('public_key')) {
                $agentApi->save();
            }
            if (filled($agentApi->public_key)) {
                $bookNowQuery['ap'] = (string) $agentApi->public_key;
            }
        }

        $bookNowUrl = rtrim((string) env('WEB_URL'), '/') . '?' . http_build_query($bookNowQuery);

        return view('pages.dashboard.index', [
            'title' => 'Dashboard',
            'salesPartner' => $salesPartner,
            'displayName' => $displayName,
            'greeting' => $greeting,
            'greetingEn' => $greetingEn,
            'role' => $user->role,
            'bookNowUrl' => $bookNowUrl,
        ]);
    }
}
