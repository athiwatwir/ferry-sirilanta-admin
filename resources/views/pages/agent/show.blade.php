@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-12">
        <x-card>
            <div class="row">
                <div class="col-12 col-lg-5 text-center border-end">
                    <h2 class="text-primary">
                        Wallet Balance {{ number_format($agent->agentAccount?->wallet_balance ?? 0) }} THB
                    </h2>
                    <hr>
                    <div class="row">

                        <div class="col-12">
                            <button class="btn btn-success">
                                <i class="fas fa-minus"></i> Deposit
                            </button>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-lg-7">
                    <h4>Agent Information</h4>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <strong>Name</strong>
                            <p>{{ $agent->name }}</p>
                        </div>
                        <div class="col-12 col-lg-6">
                            <strong>Email</strong>
                            <p>{{ $agent->user->email }}</p>
                        </div>
                    </div>


                </div>
            </div>
        </x-card>
    </div>

    <div class="col-12 ">
        <x-card>
            <div class="row">
                <div class="col-12">
                    <h4>รายการเติมเงิน (Top up)</h4>
                    <p class="text-muted small mb-2">รายการคำขอเติมเงินเข้า wallet</p>
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>Ref. / หมายเหตุ</th>
                                <th class="text-center">จำนวนเงิน</th>
                                <th class="text-center">สถานะ</th>
                                <th>สลิป</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $tx)
                            <tr>
                                <td>{{ $tx->created_at?->format('d/m/Y H:i') }}</td>
                                <td>{{ $tx->description ?? '-' }}</td>
                                <td class="text-center">
                                    <x-label-price :price="$tx->amount" />
                                </td>
                                <td class="text-center">
                                    @if(($tx->isapproved ?? '') === 'Y')
                                    <span class="badge bg-success">อนุมัติแล้ว</span>
                                    @else
                                    <span class="badge bg-warning text-dark">รออนุมัติ</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tx->image_path)
                                    <a href="{{ route('agentAccount.slip', $tx) }}" target="_blank" class="btn btn-sm btn-outline-primary">ดูสลิป</a>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(($tx->isapproved ?? '') === 'N')
                                    <form action="{{ route('agent.topup.approve', ['agent' => $agent->id, 'transaction' => $tx->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('อนุมัติรายการเติมเงินนี้?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">อนุมัติ</button>
                                    </form>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">ยังไม่มีรายการเติมเงิน</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card>
    </div>
</div>


@endsection
