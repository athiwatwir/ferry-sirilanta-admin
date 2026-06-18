@extends('layouts.default')

@section('content')

@include('pages.booking.dashboard.broker')

<div class="row">
    <div class="col-12">
        <x-card>
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-1">ประวัติการทำรายการ</h4>
                    <p class="text-muted small mb-3">รายการ Transaction จาก Agent Account</p>
                    <table class="table table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th class="text-center">จำนวนเงิน</th>
                                <th class="text-center">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $tx)
                            <tr>
                                <td>{{ $tx->created_at?->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge bg-label-primary">{{ ucfirst($tx->type ?? '-') }}</span>
                                </td>
                                <td>{{ $tx->description ?? '-' }}</td>
                                <td class="text-center">
                                    <x-label-price :price="$tx->amount" />
                                </td>
                                <td class="text-center">
                                    @if(($tx->isapproved ?? '') === 'Y')
                                    <span class="badge bg-success">อนุมัติแล้ว</span>
                                    @elseif(($tx->isapproved ?? '') === 'N')
                                    <span class="badge bg-warning text-dark">รออนุมัติ</span>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">ยังไม่มีรายการ Transaction</td>
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
