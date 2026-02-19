<a href="{{ route('agentAccount.show',['agentAccount'=>$salesPartner->agentAccount]) }}" class="menu-link">
    <i class="menu-icon icon-base ti tabler-wallet"></i>
    <div data-i18n="Page 1">Wallet <span class="badge text-bg-primary">{{ number_format($salesPartner->agentAccount->wallet_balance,2) }} THB</span></div>
</a>
