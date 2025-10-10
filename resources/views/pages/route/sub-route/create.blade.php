@extends('layouts.default')

@section('content')
<x-card>
    <x-form action="{{ route('subRoute.store') }}">
        <input type="hidden" name="route_id" id="route_id" value="{{ $route->id }}">
        <div class="row">
            <div class="col-12 col-lg-6">
                <h4><span class="text-primary">From:</span>
                    <x-station.label-name :station="$route->departStation" />
                </h4>
            </div>
            <div class="col-12 col-lg-6">
                <h4><span class="text-primary">To:</span>
                    <x-station.label-name :station="$route->destStation" />
                </h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6 border-end">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <x-form.float.input-time name="depart_time" label="Depart Time" />

                        <x-selection.time-zone name="origin_timezone" label="Depart Timezone" />
                    </div>
                    <div class="col-12 col-lg-6">

                        <x-form.float.input-time name="arrival_time" label="Arrive Time" />
                        <x-selection.time-zone name="destination_timezone" label="Arrival Timezone" />
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6 col-lg-6">
                        <x-form.float.selection name="boat_type" label="Ferry Type" :options="$ferryTypes" />
                    </div>
                    <div class="col-6 col-lg-6">
                        <x-form.float.selection name="boat_type2" label="Ferry Type#2" :options="$ferryTypes" />
                    </div>
                    <div class="col-12 col-lg-6">
                        <x-form.float.input name="seatamt" label="Seat" />
                    </div>


                    <div class="col-12 col-lg-6">
                        <x-form.float.input-time name="_close_time" label="Cut-Off(Hrs.)" :isrequire="false" help="จำนวน ชม. ก่อน  Depart Time" />
                        <input type="hidden" name="close_time" id="close_time">
                    </div>
                    <div class="col-12 col-lg-6">
                        <strong class="text-danger" id="box-result-time"></strong>
                    </div>

                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="row">
                    <div class="col-12">
                        <x-form.float.selection :options="['transport'=>'Transport Route','activity'=>'Activity Route']" name="type" label="Route Type" />
                    </div>
                </div>
                <h5 class="card-header">Price Section</h5>
                <div class="row">
                    <div class="col">
                        <x-form.float.input name="price" label="Regular Price" />
                    </div>
                    <div class="col">
                        <x-form.float.input name="child_price" label="Child Price" />
                    </div>
                    <div class="col">
                        <x-form.float.input name="infant_price" label="Infant Price" />
                    </div>
                </div>

            </div>

        </div>
    </x-form>
</x-card>
@stop


@section('script')

<script src="{{ asset('assets/vendor/libs/cleave-zen/cleave-zen.js') }}"></script>
<script src="{{ asset('js/sub-route/time-calculate.js') }}"></script>

@stop
