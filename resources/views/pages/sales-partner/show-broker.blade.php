@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-12 col-lg-8">
        <x-card>
            <div class="row">
                <div class="col-12 col-lg-4 text-center border-end">
                    <h1 class="text-primary">
                        Point {{ number_format($broker->brokerPoint->balance) }}
                    </h1>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-plus"></i> Add Point
                            </button>
                        </div>
                        <div class="col-12 col-lg-6">
                            <button class="btn btn-success">
                                <i class="fas fa-minus"></i> Earn Point
                            </button>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-lg-8">
                    <h4>Point History</h4>
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th class="text-center">Point</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-12 col-lg-4">
        <x-card>
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
        </x-card>
    </div>
</div>


@endsection
