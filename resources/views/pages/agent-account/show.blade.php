@extends('layouts.default')


@section('content')

<div class="row">
    <div class="col-12 col-lg-5">
        <x-card class="">
            <div class="row">
                <div class="col-12 col-lg-8">
                    <strong class="text-dark">Balance (THB)</strong>
                    <h3 class="text-primary">
                        <x-label-price :price="$agentAccount->wallet_balance" />
                    </h3>
                </div>
                <div class="col-12 col-lg-4 text-end">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-add-balance">Top up</button>
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-12 col-lg-7">

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

<x-modal id="modal-add-balance" title="Top up">
    <x-form type="modal" action="#">
        <div class="row">
            <div class="col-12">
                <x-form.float.input name="balance" label="Amount" />
            </div>
        </div>
    </x-form>
</x-modal>
@stop
