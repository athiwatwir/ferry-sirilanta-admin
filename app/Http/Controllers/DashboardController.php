<?php

namespace App\Http\Controllers;

use App\Models\SalesPartner;
use App\Services\BookNowService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected BookNowService $bookNowService
    ) {}

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

        return view('pages.dashboard.index', [
            'title' => 'Dashboard',
            'salesPartner' => $salesPartner,
            'displayName' => $displayName,
            'greeting' => $greeting,
            'greetingEn' => $greetingEn,
            'role' => $user->role,
            'bookNowUrl' => $this->bookNowService->buildUrl($user, $salesPartner),
        ]);
    }
}
