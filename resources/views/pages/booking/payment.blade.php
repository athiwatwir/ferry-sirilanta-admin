@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col-12 col-lg-6 mx-auto">
        <div class="row">
            <div class="col-12 text-center">
                <h3 class="text-primary">
                    <x-label-price :price="$booking->totalamt" />
                </h3>
            </div>
        </div>
        <x-form :isshow_button="false">
            <div class="row">
                <div class="col-12">
                    <x-payment.tctp-list />
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">

                    <button class="btn btn-lg btn-success">Payment</button>
                </div>
            </div>
        </x-form>

    </div>


</div>
@stop
