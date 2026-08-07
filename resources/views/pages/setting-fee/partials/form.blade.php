@php
    $fee = $fee ?? null;
    $isEdit = $fee !== null;
@endphp

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center gap-2">
            <span class="avatar avatar-sm">
                <span class="avatar-initial rounded bg-label-primary">
                    <i class="icon-base ti tabler-id icon-sm"></i>
                </span>
            </span>
            <div>
                <h5 class="mb-0">Profile</h5>
                <small class="text-muted">Name and unique code for this fee setting</small>
            </div>
        </div>
        <hr class="mt-3 mb-0">
    </div>

    <div class="col-12 col-md-6">
        <x-form.float.input
            name="name"
            label="Name"
            :value="old('name', $fee?->name)"
            placeholder="e.g. 2C2P Default"
        />
    </div>
    <div class="col-12 col-md-6">
        <x-form.float.input
            name="code"
            label="Code"
            :value="old('code', $fee?->code)"
            placeholder="e.g. 2C2P"
            help="Unique code used by the payment system"
        />
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center gap-2">
            <span class="avatar avatar-sm">
                <span class="avatar-initial rounded bg-label-info">
                    <i class="icon-base ti tabler-credit-card icon-sm"></i>
                </span>
            </span>
            <div>
                <h5 class="mb-0">Credit Card Fee</h5>
                <small class="text-muted">Applied to CC / Debit / Global Card payments</small>
            </div>
        </div>
        <hr class="mt-3 mb-0">
    </div>

    <div class="col-12 col-md-6">
        <x-form.float.selection
            name="credit_card_fee_type"
            label="Fee Type"
            :options="$feeTypes"
            :default="old('credit_card_fee_type', $fee?->credit_card_fee_type ?? 'percent')"
        />
    </div>
    <div class="col-12 col-md-6">
        <x-form.float.input
            name="credit_card_fee"
            label="Fee Value"
            type="number"
            :value="old('credit_card_fee', $fee?->credit_card_fee ?? 0)"
            min="0"
            step="0.01"
            placeholder="0.00"
            help="Percent: e.g. 5.57 · Fixed: amount in THB"
        />
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="d-flex align-items-center gap-2">
            <span class="avatar avatar-sm">
                <span class="avatar-initial rounded bg-label-success">
                    <i class="icon-base ti tabler-qrcode icon-sm"></i>
                </span>
            </span>
            <div>
                <h5 class="mb-0">Thai QR Fee</h5>
                <small class="text-muted">Applied to PromptPay / Thai QR payments</small>
            </div>
        </div>
        <hr class="mt-3 mb-0">
    </div>

    <div class="col-12 col-md-6">
        <x-form.float.selection
            name="thai_qr_fee_type"
            label="Fee Type"
            :options="$feeTypes"
            :default="old('thai_qr_fee_type', $fee?->thai_qr_fee_type ?? 'percent')"
        />
    </div>
    <div class="col-12 col-md-6">
        <x-form.float.input
            name="thai_qr_fee"
            label="Fee Value"
            type="number"
            :value="old('thai_qr_fee', $fee?->thai_qr_fee ?? 0)"
            min="0"
            step="0.01"
            placeholder="0.00"
            help="Percent: e.g. 0.00 · Fixed: amount in THB"
        />
    </div>
</div>
