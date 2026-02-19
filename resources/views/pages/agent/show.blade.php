@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-12">
        <x-card>
            <div class="row">
                <div class="col-12 col-lg-5 text-center border-end">
                    <h2 class="text-primary">
                        Wallet Balance {{ number_format($agent->agentAccount->wallet_balance) }}THB
                    </h2>
                    <hr>
                    <div class="row">

                        <div class="col-12">
                            <button class="btn btn-success">
                                <i class="fas fa-minus"></i> Deposit
                            </button>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-lg-7">
                    <h4>Agent Information</h4>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <strong>Name</strong>
                            <p>{{ $agent->name }}</p>
                        </div>
                        <div class="col-12 col-lg-6">
                            <strong>Email</strong>
                            <p>{{ $agent->user->email }}</p>
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
                    <h4>Transaction History</h4>
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
</div>


@endsection
