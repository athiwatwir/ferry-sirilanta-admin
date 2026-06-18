@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-12">
        <x-card>
            <div class="row">
                <div class="col-12 col-lg-5 d-flex align-items-center justify-content-center border-end border-lg-bottom-0 pb-4 pb-lg-0 mb-4 mb-lg-0">
                    <div class="text-center py-2 px-3 w-100">
                        <div class="avatar avatar-lg mx-auto mb-3">
                            <span class="avatar-initial rounded-circle bg-label-primary">
                                <i class="icon-base ti tabler-wallet icon-lg text-primary"></i>
                            </span>
                        </div>
                        <p class="text-muted small text-uppercase fw-medium mb-2">Wallet Balance</p>
                        <h2 class="display-5 fw-bold text-primary mb-2 lh-1">
                            {{ number_format($agent->agentAccount?->wallet_balance ?? 0) }}
                        </h2>
                        <span class="badge bg-label-primary px-3 py-2 fs-6">THB</span>
                        <p class="text-muted small mt-3 mb-0">ยอดคงเหลือในระบบ Wallet สำหรับออกตั๋ว</p>
                    </div>
                </div>
                <div class="col-12 col-lg-7">
                    <div class="row">
                        <div class="col-12 col-lg-10">
                            <h4>Agent Information</h4>
                        </div>
                        <div class="col-12 col-lg-2 text-end">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-edit-agent">แก้ไข</button>
                        </div>
                    </div>


                    <hr>
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <strong>Name</strong>
                            <p>{{ $agent->name }}</p>
                        </div>
                        <div class="col-12 col-lg-3">
                            <strong>Code</strong>
                            <p>{{ $agent->code }}</p>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Email</strong>
                            <p>{{ $agent->user?->email ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-lg-2">
                            <strong>Discount%</strong>
                            <p>{{ $agent->discount }}</p>
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

@section('modal')
<x-modal id="modal-edit-agent" title="แก้ไขข้อมูล Agent">
    <x-form type="modal" :action="route('agent.update', $agent)" method="POST">
        @method('PUT')
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.float.input name="name" label="Name" :value="old('name', $agent->name)" :isrequire="true" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.input name="code" label="Code" :value="old('code', $agent->code)" :isrequire="false" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.input name="email" label="Email" type="email" :value="old('email', $agent->user?->email)" :isrequire="true" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.input name="discount" label="Discount %" type="number" :value="old('discount', $agent->discount)" :isrequire="false" placeholder="0" min="0" max="100" step="0.01" />
            </div>
            <div class="col-12">
                <x-form.float.input name="password" label="Password (เว้นว่างถ้าไม่เปลี่ยน)" type="password" :isrequire="false" value="" />
            </div>
        </div>
    </x-form>
</x-modal>
@endsection

@section('script')
@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('modal-edit-agent');
        if (el && typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(el).show();
        }
    });

</script>
@endif
@endsection
