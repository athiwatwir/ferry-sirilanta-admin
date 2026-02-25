@extends('layouts.default')


@section('content')

<div class="row">
    <div class="col-12 col-lg-5">
        <x-card class="">
            <div class="row">
                <div class="col-12 col-lg-8">
                    <strong class="text-dark">Balance (THB)</strong>
                    <h3 class="text-primary">
                        <x-label-price :price="$agentAccount->wallet_balance" />
                    </h3>
                </div>
                <div class="col-12 col-lg-4 text-end">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-add-balance">Top up</button>
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-12 col-lg-7">

    </div>
</div>

<div class="row">
    <div class="col-12">
        <x-card>
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-0">Top up wallet</h4>
                    <p>รายการคำขอเติมเงินเข้า wallet ของ agent</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <x-table.datatabble>
                        <thead>
                            <tr>
                                <th>เวลา</th>
                                <th>Agent</th>
                                <th>Top Up Amt</th>
                                <th>Ref.</th>
                                <th>สลิป</th>
                                <th class="text-center">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $tx)
                            <tr>
                                <td>{{ $tx->created_at?->format('d/m/Y H:i') }}</td>
                                <td>{{ $agentAccount->salesPartner?->name ?? '-' }}</td>
                                <td>
                                    <x-label-price :price="$tx->amount" />
                                </td>
                                <td>{{ $tx->description ?? '-' }}</td>
                                <td>
                                    @if($tx->image_path)
                                    <a href="{{ route('agentAccount.slip', $tx) }}" target="_blank" class="btn btn-sm btn-outline-primary">ดูสลิป</a>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(($tx->isapproved ?? '') === 'Y')
                                    <span class="badge bg-success">อนุมัติแล้ว</span>
                                    @elseif(($tx->isapproved ?? '') === 'N')
                                    <span class="badge bg-warning text-dark">รออนุมัติ</span>
                                    @else
                                    <span class="badge bg-secondary">{{ $tx->isapproved ?? '-' }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">ยังไม่มีรายการ</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </x-table.datatabble>
                </div>
            </div>
        </x-card>
    </div>
</div>

@stop

@section('modal')

<x-modal id="modal-add-balance" title="เติมเงิน (Top up)">
    <x-form type="modal" :action="route('agentAccount.topUp', $agentAccount)" method="POST">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label">สลิปการโอน <strong class="text-danger">*</strong></label>
                <input type="file" class="form-control" name="slip" accept="image/*" required>
                <div class="form-text">รองรับ JPG, PNG, GIF สูงสุด 5 MB</div>
            </div>
            <div class="col-12 mb-3">
                <x-form.float.input name="amount" label="จำนวนเงิน (THB)" type="number" :isrequire="true" placeholder="0.00" />
            </div>
            <div class="col-12">
                <x-form.float.input name="description" label="Ref. / หมายเหตุ" :isrequire="false" placeholder="อ้างอิงการโอน (ถ้ามี)" />
            </div>
        </div>
    </x-form>
</x-modal>
@stop
