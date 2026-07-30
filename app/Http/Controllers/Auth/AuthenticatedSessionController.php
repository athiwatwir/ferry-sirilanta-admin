<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Agent;
use App\Models\SalesPartner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $agent = Agent::whereId($user->agent_id)->first();
        Session::put('agent', $agent);

        Session::forget('agentApi');
        if ($user->sales_partner_id) {
            $salesPartner = SalesPartner::with('agentApi')->find($user->sales_partner_id);
            $agentApi = $salesPartner?->agentApi;
            if ($agentApi) {
                $agentApi->ensurePublicKey();
                if ($agentApi->isDirty('public_key')) {
                    $agentApi->save();
                }
                Session::put('agentApi', $agentApi);
            }

            // อย่าใช้ intended() — มักถูกเก็บเป็น "/" (booking) ตอนถูกเด้งมา login
            return redirect()->route('dashboard.index');
        }

        return redirect()->intended(route('booking.index', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
