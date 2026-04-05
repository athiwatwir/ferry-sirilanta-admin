@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-12">
        <x-card>
            <div class="row">
                <div class="col-12 col-lg-5 text-center border-end">
                    <div class="row">
                        <div class="col">
                            <h4 class="mb-0">
                                Credit Used
                            </h4>
                            <h2 class="text-success">{{ number_format($broker->agentAccount?->credit_balance ?? 0) }} THB</h2>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-1" data-bs-toggle="modal" data-bs-target="#modal-credit-used">Clear Credit</button>
                        </div>
                        <div class="col">
                            <h4 class="mb-0">
                                Credit Limit
                            </h4>
                            <h2 class="text-danger">{{ number_format($broker->agentAccount?->credit_limit ?? 0) }} THB</h2>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-1" data-bs-toggle="modal" data-bs-target="#modal-credit-limit">Update Credit Limit</button>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-lg-7">
                    <div class="row">
                        <div class="col-12 col-lg-10">
                            <h4>Broker Information</h4>
                        </div>
                        <div class="col-12 col-lg-2 text-end">
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal-edit-broker">Edit</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <strong>Name</strong>
                            <p>{{ $broker->name }}</p>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Code</strong>
                            <p>{{ $broker->code }}</p>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Email</strong>
                            <p>{{ $broker->user->email }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('broker.user', $broker) }}" class="btn btn-outline-secondary">รายชื่อพนักงาน ({{ $broker->users->count() }})</a>
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
                    <h4>Booking</h4>
                    <x-table.datatabble class="booking-table">
                        <thead>
                            <tr>
                                <th class="">Booking Date</th>
                                <th>Travel Date</th>
                                <th>Invoice No</th>

                                <th>Ticket No</th>
                                <th>Type</th>
                                <th>Customer</th>
                                <th><i class="icon-base ti tabler-friends"></i></th>
                                <th class="text-end">Price</th>
                                <th>Processing Fee</th>
                                <th class="text-end">Total Price</th>
                                <th>Route</th>
                                <th>Status</th>

                                <th>User</th>

                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </x-table.datatabble>
                </div>
            </div>
        </x-card>
    </div>
</div>

@stop


@section('modal')
<x-modal id="modal-credit-limit" title="แก้ไข Credit Limit">
    <x-form type="modal" :action="route('broker.updateCreditLimit', $broker)" method="POST">
        @method('PATCH')
        <div class="row">
            <div class="col-12">
                <x-form.float.input name="credit_limit" label="Credit Limit (THB)" type="number" :value="old('credit_limit', $broker->agentAccount->credit_limit ?? 0)" :isrequire="true" placeholder="0" min="0" step="0.01" />
            </div>
        </div>
    </x-form>
</x-modal>
<x-modal id="modal-credit-used" title="Clear Credit">
    <x-form type="modal" :action="route('broker.updateCreditUsed', $broker)" method="POST">
        @method('PATCH')
        <div class="row">
            <div class="col-12">
                <x-form.float.input name="amount" label="จำนวนเงินที่จะชำระ (THB)" type="number" :value="old('credit_used', $broker->agentAccount->credit_balance ?? 0)" :isrequire="true" placeholder="0" min="0" step="0.01" />
            </div>
        </div>
    </x-form>
</x-modal>
<x-modal id="modal-edit-broker" title="แก้ไขข้อมูล Broker">
    <x-form type="modal" :action="route('broker.update', $broker)" method="POST">
        @method('PUT')
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.float.input name="name" label="Name" :value="old('name', $broker->name)" :isrequire="true" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.input name="code" label="Code" :value="old('code', $broker->code)" :isrequire="false" />
            </div>
            <div class="col-12">
                <x-form.float.input name="email" label="Email" type="email" :value="old('email', $broker->user?->email)" :isrequire="true" />
            </div>
            <div class="col-12">
                <x-form.float.input name="password" label="Password (เว้นว่างถ้าไม่เปลี่ยน)" type="password" :isrequire="false" value="" />
            </div>
        </div>
    </x-form>
</x-modal>
@stop

@section('script')
@if ($errors->hasAny(['name', 'code', 'email', 'password']))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('modal-edit-broker');
        if (el && typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(el).show();
        }
    });
</script>
@endif
@stop
