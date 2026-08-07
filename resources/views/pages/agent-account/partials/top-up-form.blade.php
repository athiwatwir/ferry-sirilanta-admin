@php
$embed = $embed ?? false;
$returnEmbed = $embed ? '1' : '0';
$prefillAmount = old('amount', $amount ?? '');
$prefillMethod = old('payment_type', $method ?? '');
@endphp

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

<div id="topup-panel" class="p-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h5 class="mb-0">เติมเงิน (Top up)</h5>
            <strong class="">
                Amount <span>
                    <x-label-price :price="(float) ($agentAccount->wallet_balance ?? 0) + (float) ($prefillAmount ?: 0)" /></span>
                Balance:
                <strong class="text-primary">
                    <x-label-price :price="$agentAccount->wallet_balance" /></strong>
                @if ($prefillAmount !== '' && $prefillAmount !== null)
                · Amount: <strong>{{ number_format((float) $prefillAmount, 2) }} THB</strong>
                @endif
            </strong>
        </div>
        @if ($agentAccount->salesPartner)
        <span class="badge bg-label-secondary">{{ $agentAccount->salesPartner->name }}</span>
        @endif
    </div>

    <div id="topup-step-choose" class="topup-step">
        <p class="text-muted mb-3">เลือกช่องทางการชำระเงิน</p>
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <button type="button" class="topup-method-card w-100" data-topup-method="card">
                    <span class="avatar avatar-md">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="icon-base ti tabler-credit-card"></i>
                        </span>
                    </span>
                    <h6 class="mb-1">บัตรเครดิต / เดบิต</h6>
                    <small class="text-muted">Visa, Mastercard,UnionPay</small>
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
    </div>

    <div id="topup-step-transfer" class="topup-step d-none">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <button type="button" class="btn btn-sm btn-label-secondary topup-back">
                <i class="icon-base ti tabler-arrow-left me-1"></i>ย้อนกลับ
            </button>
            <span class="badge bg-label-success">โอนเงิน / แนบสลิป</span>
        </div>
        <x-form id="frm-topup-transfer" :action="route('agentAccount.topUp', $agentAccount, false)" method="POST" :isshow_button="true" :backUrl="route('agentAccount.topUpPage', array_filter(['agentAccount' => $agentAccount, 'embed' => $returnEmbed, 'amount' => $prefillAmount ?: null]), false)">
            <input type="hidden" name="payment_type" value="transfer">
            <input type="hidden" name="embed" value="{{ $returnEmbed }}">
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">สลิปการโอน <strong class="text-danger">*</strong></label>
                    <input type="file" class="form-control" name="slip" accept="image/*" required>
                    <div class="form-text">รองรับ JPG, PNG, GIF สูงสุด 5 MB</div>
                </div>
                <div class="col-12 mb-3">
                    <x-form.float.input name="amount" label="จำนวนเงิน (THB)" type="number" :value="$prefillAmount" :isrequire="true" placeholder="0.00" step="0.01" min="0.01" />
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
        <x-form id="frm-topup-card" :action="route('agentAccount.topUp', $agentAccount, false)" method="POST" :isshow_button="false">
            <input type="hidden" name="payment_type" value="card">
            <input type="hidden" name="embed" value="{{ $returnEmbed }}">
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
                        <input type="number" class="form-control" id="card_amount" name="amount" required value="{{ $prefillAmount }}" placeholder="0.00" step="0.01" min="0.01">
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
        <x-form id="frm-topup-etc" :action="route('agentAccount.topUp', $agentAccount, false)" method="POST" :isshow_button="false">
            <input type="hidden" name="payment_type" value="etc">
            <input type="hidden" name="embed" value="{{ $returnEmbed }}">
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
                        <input type="number" class="form-control" id="etc_amount" name="amount" required value="{{ $prefillAmount }}" placeholder="0.00" step="0.01" min="0.01">
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
</div>

<script>
    (function() {
        const panel = document.getElementById('topup-panel');
        if (!panel) return;

        const steps = {
            choose: document.getElementById('topup-step-choose')
            , transfer: document.getElementById('topup-step-transfer')
            , card: document.getElementById('topup-step-card')
            , etc: document.getElementById('topup-step-etc')
        , };

        function showStep(key) {
            Object.values(steps).forEach((el) => el && el.classList.add('d-none'));
            (steps[key] || steps.choose).classList.remove('d-none');
        }

        panel.querySelectorAll('[data-topup-method]').forEach((btn) => {
            btn.addEventListener('click', function() {
                showStep(this.getAttribute('data-topup-method'));
            });
        });

        panel.querySelectorAll('.topup-back').forEach((btn) => {
            btn.addEventListener('click', function() {
                showStep('choose');
            });
        });

        var initialMethod = @json($prefillMethod);
        if (['transfer', 'card', 'etc'].indexOf(initialMethod) !== -1) {
            showStep(initialMethod);
        }
    })();

</script>
