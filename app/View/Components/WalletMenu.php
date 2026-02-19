<?php

namespace App\View\Components;

use App\Models\SalesPartner;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class WalletMenu extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $salesPartnerId = Auth::user()->sales_partner_id;

        $salesPartner = SalesPartner::whereId($salesPartnerId)->with(['agentAccount'])->first();



        return view('components.wallet-menu', compact('salesPartner'));
    }
}
