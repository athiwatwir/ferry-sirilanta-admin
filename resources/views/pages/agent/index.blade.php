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
    <div class="row">
        <div class="col-9 col-lg-6">

        </div>
        <div class="col-3 col-lg-6 text-end">
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
