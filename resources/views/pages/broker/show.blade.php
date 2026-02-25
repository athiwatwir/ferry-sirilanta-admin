@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-12">
        <x-card>
            <div class="row">
                <div class="col-12 col-lg-5 text-center border-end">
                    <h2 class="text-primary">
                        Credit Used {{ number_format($broker->agentAccount?->credit_balance ?? 0) }} THB
                    </h2>
                    <h4 class="text-danger">
                        Credit Limit {{ number_format($broker->agentAccount?->credit_limit ?? 0) }} THB <button type="button" class="btn btn-sm btn-outline-secondary mt-1" data-bs-toggle="modal" data-bs-target="#modal-credit-limit">แก้ไข</button>
                    </h4>

                </div>
                <div class="col-12 col-lg-7">
                    <h4>Broker Information</h4>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <strong>Name</strong>
                            <p>{{ $broker->name }}</p>
                        </div>
                        <div class="col-12 col-lg-6">
                            <strong>Email</strong>
                            <p>{{ $broker->user->email }}</p>
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
@stop
