@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col-12 col-xl-10 mx-auto">
        <div class="d-flex align-items-start p-3 rounded bg-label-warning mb-4">
            <div class="avatar avatar-sm me-3 flex-shrink-0 mt-1">
                <span class="avatar-initial rounded bg-warning">
                    <i class="icon-base ti tabler-edit icon-sm"></i>
                </span>
            </div>
            <div>
                <h6 class="mb-1 fw-semibold text-warning">Edit Fee Setting</h6>
                <p class="mb-0 text-body small">
                    Update fee values for <strong>{{ $fee->name }}</strong> ({{ $fee->code }}).
                </p>
            </div>
        </div>

        <x-card>
            <x-form :action="route('settingFee.update', $fee)" :backUrl="route('settingFee.index')" method="POST">
                @method('PUT')
                @include('pages.setting-fee.partials.form', ['fee' => $fee])
            </x-form>
        </x-card>
    </div>
</div>
@endsection
