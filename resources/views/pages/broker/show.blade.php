@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-12">
        <x-card>
            <div class="row">

                <div class="col-12 col-lg-6 border-end">
                    <h4>Broker Information</h4>
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
                </div>

                <div class="col-12 col-lg-6">
                    <a href="" class="btn btn-outline-primary">พนักงานขาย</a>
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


@endsection
