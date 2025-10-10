@extends('layouts.default')


@section('content')
<x-agent.header :agent="$agent" active="wallet" />

<div class="row">
    <div class="col-12 col-lg-5">
        @php
        $class='text-bg-secondary bg-light';
        if($agent->is_use_wallet=='Y'){
        $class = 'text-bg-primary';
        }
        @endphp
        <x-card class="{{ $class }}">
            <div class="row">
                <div class="col-12 col-lg-8">
                    <strong class="text-dark">Balance (THB)</strong>
                    <h3 class="text-white">
                        <x-label-price :price="$agent->wallet->balance" />
                    </h3>
                </div>
                <div class="col-12 col-lg-4 text-end">
                    @if ($agent->is_use_wallet=='Y')
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-add-balance">Add Balance</button>
                    @endif
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-12 col-lg-7">
        <x-card>
            <x-form :action="route('wallet.update',['wallet'=>$agent->wallet])">
                @method('put')
                <div class="row">

                    <div class="col-12 col-lg-6">
                        <x-form.float.selection :options="['amount'=>'Amount(THB)','percent'=>'%']" name="use_over_limit_type" label="Over Limit Type" :default="$agent->wallet->use_over_limit_type" />
                    </div>
                    <div class="col-12 col-lg-6">
                        <x-form.float.input name="use_over_limit" label="Over Limit" :isrequire="false" :value="$agent->wallet->use_over_limit" />
                    </div>
                </div>
            </x-form>
        </x-card>
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
                                <th>time</th>
                                <th>Document No</th>
                                <th>Agent</th>
                                <th>Top Up Amt</th>
                                <th>Ref.</th>
                                <th></th>
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

<x-modal id="modal-add-balance" title="Add Balance">
    <x-form type="modal" :action="route('wallet.addBalance',['wallet'=>$agent->wallet])">
        <div class="row">
            <div class="col-12">
                <x-form.float.input name="balance" label="Amount" />
            </div>
        </div>
    </x-form>
</x-modal>
@stop
