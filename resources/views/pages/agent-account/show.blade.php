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
                                <th>Amount</th>
                                <th>ช่องทาง</th>
                                <th>Ref.</th>
                                <th>สลิป</th>
                                <th class="text-center">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $tx)
                            @php
                            $desc = (string) ($tx->description ?? '');
                            $isCard = str_starts_with($desc, '[CARD]');
                            $isEtc = str_starts_with($desc, '[ETC]');
                            $refText = $desc;
                            foreach (['[CARD]', '[ETC]'] as $tag) {
                            $refText = trim(str_replace($tag, '', $refText));
                            }
                            @endphp
                            <tr>
                                <td>{{ $tx->created_at?->format('d/m/Y H:i') }}</td>
                                <td>{{ $agentAccount->salesPartner?->name ?? '-' }}</td>
                                <td>
                                    <x-label-price :price="$tx->type=='topup' ? $tx->amount : $tx->amount*-1" />
                                </td>
                                <td>
                                    @if($tx->type=='topup')
                                    @if ($isCard)
                                    <span class="badge bg-label-info">
                                        <i class="icon-base ti tabler-credit-card me-1"></i>บัตรเครดิต
                                    </span>
                                    @elseif ($isEtc)
                                    <span class="badge bg-label-warning">
                                        <i class="icon-base ti tabler-qrcode me-1"></i>QR / Wallet
                                    </span>
                                    @else
                                    <span class="badge bg-label-success">
                                        <i class="icon-base ti tabler-building-bank me-1"></i>โอนเงิน
                                    </span>
                                    @endif
                                    @endif
                                </td>
                                <td>{{ $refText !== '' ? $refText : '-' }}</td>
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
                            {{-- ไม่ใส่แถว colspan: DataTables จะแสดง emptyTable เอง --}}
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
<style>
    .topup-method-card {
        border: 1px solid #d9dee3;
        border-radius: 0.75rem;
        padding: 1.25rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
        height: 100%;
        background: #fff;
    }

    .topup-method-card:hover {
        border-color: #696cff;
        box-shadow: 0 0.25rem 0.75rem rgba(105, 108, 255, 0.12);
        background: #f8f8ff;
    }

    .topup-method-card .avatar {
        margin: 0 auto 0.75rem;
    }

</style>

<x-modal id="modal-add-balance" title="เติมเงิน (Top up)" size="modal-lg">
    <div id="topup-step-choose" class="topup-step">
        <p class="text-muted mb-3">เลือกช่องทางการชำระเงิน</p>
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <button type="button" class="topup-method-card w-100" data-topup-method="transfer">
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="icon-base ti tabler-building-bank"></i>
                        </span>
                    </span>
                    <h6 class="mb-1">โอนเงิน / สลิป</h6>
                    <small class="text-muted">โอนแล้วอัปโหลดสลิปรออนุมัติ</small>
                </button>
            </div>
            <div class="col-12 col-md-4">
                <button type="button" class="topup-method-card w-100" data-topup-method="card">
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="icon-base ti tabler-credit-card"></i>
                        </span>
                    </span>
                    <h6 class="mb-1">บัตรเครดิต / เดบิต</h6>
                    <small class="text-muted">Visa, Mastercard, JCB ฯลฯ</small>
                </button>
            </div>
            <div class="col-12 col-md-4">
                <button type="button" class="topup-method-card w-100" data-topup-method="etc">
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="icon-base ti tabler-qrcode"></i>
                        </span>
                    </span>
                    <h6 class="mb-1">QR / PromptPay / Wallet</h6>
                    <small class="text-muted">ชำระผ่านหน้า 2C2P</small>
                </button>
            </div>
        </div>
        <div class="text-center mt-4">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>

    <div id="topup-step-transfer" class="topup-step d-none">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <button type="button" class="btn btn-sm btn-label-secondary topup-back">
                <i class="icon-base ti tabler-arrow-left me-1"></i>ย้อนกลับ
            </button>
            <span class="badge bg-label-success">โอนเงิน / แนบสลิป</span>
        </div>
        <x-form id="frm-topup-transfer" type="modal" :action="route('agentAccount.topUp', $agentAccount)" method="POST">
            <input type="hidden" name="payment_type" value="transfer">
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">สลิปการโอน <strong class="text-danger">*</strong></label>
                    <input type="file" class="form-control" name="slip" accept="image/*" required>
                    <div class="form-text">รองรับ JPG, PNG, GIF สูงสุด 5 MB</div>
                </div>
                <div class="col-12 mb-3">
                    <x-form.float.input name="amount" label="จำนวนเงิน (THB)" type="number" :isrequire="true" placeholder="0.00" step="0.01" min="0.01" />
                </div>
                <div class="col-12">
                    <x-form.float.input name="description" label="Ref. / หมายเหตุ" :isrequire="false" placeholder="อ้างอิงการโอน (ถ้ามี)" />
                </div>
            </div>
        </x-form>
    </div>

    <div id="topup-step-card" class="topup-step d-none">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <button type="button" class="btn btn-sm btn-label-secondary topup-back">
                <i class="icon-base ti tabler-arrow-left me-1"></i>ย้อนกลับ
            </button>
            <span class="badge bg-label-info">บัตรเครดิต / เดบิต</span>
        </div>
        <x-form id="frm-topup-card" type="modal" :action="route('agentAccount.topUp', $agentAccount)" method="POST" :isshow_button="false">
            <input type="hidden" name="payment_type" value="card">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="d-flex align-items-start p-3 rounded bg-label-info">
                        <div class="avatar avatar-sm me-3 flex-shrink-0">
                            <span class="avatar-initial rounded bg-info">
                                <i class="icon-base ti tabler-credit-card icon-sm"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-1 text-info">ชำระด้วยบัตร</h6>
                            <p class="mb-0 small text-body">ใช้ Merchant CREDIT — หน้า 2C2P จะโชว์ช่องทางบัตร</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="card_amount" name="amount" required value="{{ old('amount') }}" placeholder="0.00" step="0.01" min="0.01">
                        <label for="card_amount">จำนวนเงิน (THB) <strong class="text-danger">*</strong></label>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="card_description" name="description" value="{{ old('description') }}" placeholder="หมายเหตุ (ถ้ามี)">
                        <label for="card_description">Ref. / หมายเหตุ</label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col text-center">
                    <button type="button" class="btn btn-label-secondary waves-effect me-2 topup-back">ย้อนกลับ</button>
                    <button type="submit" class="btn btn-info waves-effect">
                        <i class="icon-base ti tabler-credit-card-pay me-1"></i>ไปชำระเงิน
                    </button>
                </div>
            </div>
        </x-form>
    </div>

    <div id="topup-step-etc" class="topup-step d-none">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <button type="button" class="btn btn-sm btn-label-secondary topup-back">
                <i class="icon-base ti tabler-arrow-left me-1"></i>ย้อนกลับ
            </button>
            <span class="badge bg-label-warning">QR / PromptPay / Wallet</span>
        </div>
        <x-form id="frm-topup-etc" type="modal" :action="route('agentAccount.topUp', $agentAccount)" method="POST" :isshow_button="false">
            <input type="hidden" name="payment_type" value="etc">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="d-flex align-items-start p-3 rounded bg-label-warning">
                        <div class="avatar avatar-sm me-3 flex-shrink-0">
                            <span class="avatar-initial rounded bg-warning">
                                <i class="icon-base ti tabler-qrcode icon-sm"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-1 text-warning">ชำระด้วย QR / Wallet</h6>
                            <p class="mb-0 small text-body">ใช้ Merchant ETC — หน้า 2C2P จะโชว์ PromptPay, QR, Digital Wallet ตามที่เปิดไว้</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="etc_amount" name="amount" required value="{{ old('amount') }}" placeholder="0.00" step="0.01" min="0.01">
                        <label for="etc_amount">จำนวนเงิน (THB) <strong class="text-danger">*</strong></label>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="etc_description" name="description" value="{{ old('description') }}" placeholder="หมายเหตุ (ถ้ามี)">
                        <label for="etc_description">Ref. / หมายเหตุ</label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col text-center">
                    <button type="button" class="btn btn-label-secondary waves-effect me-2 topup-back">ย้อนกลับ</button>
                    <button type="submit" class="btn btn-warning waves-effect">
                        <i class="icon-base ti tabler-qrcode me-1"></i>ไปชำระเงิน
                    </button>
                </div>
            </div>
        </x-form>
    </div>
</x-modal>
@stop

@section('script')
<script>
    (function() {
        const modal = document.getElementById('modal-add-balance');
        if (!modal) return;

        const steps = {
            choose: document.getElementById('topup-step-choose')
            , transfer: document.getElementById('topup-step-transfer')
            , card: document.getElementById('topup-step-card')
            , etc: document.getElementById('topup-step-etc')
        , };

        function showStep(key) {
            Object.values(steps).forEach((el) => el.classList.add('d-none'));
            (steps[key] || steps.choose).classList.remove('d-none');
        }

        modal.querySelectorAll('[data-topup-method]').forEach((btn) => {
            btn.addEventListener('click', function() {
                showStep(this.getAttribute('data-topup-method'));
            });
        });

        modal.querySelectorAll('.topup-back').forEach((btn) => {
            btn.addEventListener('click', function() {
                showStep('choose');
            });
        });

        modal.addEventListener('hidden.bs.modal', function() {
            showStep('choose');
            modal.querySelectorAll('form').forEach((form) => form.reset());
        });
    })();

</script>
@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('modal-add-balance');
        if (!el || typeof bootstrap === 'undefined') return;

        new bootstrap.Modal(el).show();

        var paymentType = @json(old('payment_type', 'transfer'));
        var map = {
            transfer: 'topup-step-transfer'
            , card: 'topup-step-card'
            , etc: 'topup-step-etc'
        };
        ['topup-step-choose', 'topup-step-transfer', 'topup-step-card', 'topup-step-etc'].forEach(function(id) {
            document.getElementById(id).classList.add('d-none');
        });
        document.getElementById(map[paymentType] || 'topup-step-transfer').classList.remove('d-none');
    });

</script>
@endif
@stop
