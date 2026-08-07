@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col-12 col-xl-10 mx-auto">
        <div class="d-flex align-items-start p-3 rounded bg-label-primary mb-4">
            <div class="avatar avatar-sm me-3 flex-shrink-0 mt-1">
                <span class="avatar-initial rounded bg-primary">
                    <i class="icon-base ti tabler-plus icon-sm"></i>
                </span>
            </div>
            <div>
                <h6 class="mb-1 fw-semibold text-primary">Create Fee Setting</h6>
                <p class="mb-0 text-body small">
                    Add a new payment fee profile for Credit Card and Thai QR channels.
                </p>
            </div>
        </div>

        <x-card>
            <x-form :action="route('settingFee.store')" :backUrl="route('settingFee.index')">
                @include('pages.setting-fee.partials.form')
            </x-form>
        </x-card>
    </div>
</div>
@endsection
