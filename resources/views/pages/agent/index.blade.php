@extends('layouts.default')

@section('content')
@if($pendingTopUpTotal > 0)
<div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
    <i class="ti ti-alert-circle me-2" style="font-size: 1.25rem;"></i>
    <div class="flex-grow-1">
        <strong>มีรายการเติมเงินรออนุมัติ {{ $pendingTopUpTotal }} รายการ</strong>
        <span class="text-muted ms-1">— กรุณาไปที่หน้ารายละเอียด Agent แล้วกดอนุมัติ</span>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<x-card>
    <div class="row align-items-center mb-3">
        <div class="col-12 col-lg-8">
            <div class="d-flex align-items-start p-3 rounded bg-label-primary">
                <div class="avatar avatar-sm me-3 flex-shrink-0 mt-1">
                    <span class="avatar-initial rounded bg-primary">
                        <i class="icon-base ti tabler-wallet icon-sm"></i>
                    </span>
                </div>
                <div>
                    <h6 class="mb-1 fw-semibold text-primary">ระบบ Agent (Wallet)</h6>
                    <p class="mb-2 mb-md-3 text-body small">
                        Agent สำหรับจำหน่ายตั๋วเรือ Ferry โดยลูกค้าชำระเงินผ่านระบบ Wallet แบบเติมเงิน
                        สามารถตรวจสอบยอดคงเหลือ หักยอดอัตโนมัติ และออกตั๋วโดยสารได้ทันที
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-label-primary">
                            <i class="icon-base ti tabler-credit-card-pay icon-xs me-1"></i>เติมเงิน Wallet
                        </span>
                        <span class="badge bg-label-success">
                            <i class="icon-base ti tabler-chart-bar icon-xs me-1"></i>ตรวจสอบยอดคงเหลือ
                        </span>
                        <span class="badge bg-label-info">
                            <i class="icon-base ti tabler-receipt icon-xs me-1"></i>ออกตั๋วทันที
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 text-lg-end mt-3 mt-lg-0">
            <x-button.new :href="route('agent.create', ['type' => 'agent'])" />
        </div>
    </div>
    <hr>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Code</th>
                <th>Email</th>
                <th>Discount%</th>
                <th class="text-end">Wallet Balance</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agents as $agent)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    {{ $agent->name }}
                    @if(($agent->pending_topup_count ?? 0) > 0)
                    <span class="badge bg-warning text-dark ms-1" title="รายการเติมเงินรออนุมัติ">รออนุมัติ {{ $agent->pending_topup_count }}</span>
                    @endif
                </td>
                <td>{{ $agent->code }}</td>
                <td>{{ $agent->user?->email ?? '-' }}</td>
                <td>{{ $agent->discount ?? 0 }}</td>
                <td class="text-end">
                    <x-label-price :price="$agent->agentAccount?->wallet_balance ?? 0" />
                </td>
                <td class="text-end">
                    <a href="{{ route('agent.show', ['agent' => $agent]) }}" class="btn btn-outline-secondary">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-card>
@endsection
