@extends('layouts.default')

@section('content')
<x-card>
    <x-form :isshow_button="false" method="GET">
        <div class="row">
            <div class="col-12 col-lg-5">
                <x-station.selection label="From" name="depart_station_id" :selected="$depart_station_id" :agentId="env('AGENT_ID')" type="depart" />
            </div>
            <div class="col-12 col-lg-5">
                <x-station.selection label="From" :isrequire="false" name="dest_station_id" :selected="$dest_station_id" :agentId="env('AGENT_ID')" type="dest" />
            </div>
            <div class="col-12 col-lg-2">
                <x-form.float.date-picker label="Travel Date" name="travel_date" />
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-lg btn-success"><i class="icon-base ti tabler-search"></i>Search</button>
            </div>
        </div>
    </x-form>
</x-card>

@if (!empty($subRoutes))
@foreach ($subRoutes as $item)
<x-card>
    <div class="row">
        <div class="col-12 col-lg-4">
            <span>{{ $item['departure_station']['name'] }} [{{ $item['departure_station']['nickname'] }}] @if (!empty($item['departure_station']['piername']))
                <small>({{ $item['departure_station']['piername'] }})</small>
                @endif </span>
            <h5>{{ $item['departure_time'] }} ({{ $item['departure_timezone'] }})</h5>
        </div>
        <div class="col-12 col-lg-4">
            <span>{{ $item['destination_station']['name'] }} [{{ $item['destination_station']['nickname'] }}] @if (!empty($item['destination_station']['piername']))
                <small>({{ $item['destination_station']['piername'] }})</small>
                @endif </span>
            <h5>{{ $item['arrival_time'] }} ({{ $item['arrival_timezone'] }})</h5>
        </div>
        <div class="col-12 col-lg-3">
            <div class="row">
                <div class="col-4">
                    <small>Regular</small>
                    <x-label-price :price="$item['prices']['regular']" />
                </div>
                <div class="col-4">
                    <small>Child</small>
                    <x-label-price :price="$item['prices']['child']" />
                </div>
                <div class="col-4">
                    <small>infant</small>
                    <x-label-price :price="$item['prices']['infant']" />
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-1">
            <div class="d-grid mx-auto">
                <a href="{{ route('booking.create',['travel_date'=>$travel_date,'sub_route_id'=>$item['id']]) }}" class="btn btn-secondary btn-lg" type="button">SELECT</a>
            </div>
        </div>
    </div>
</x-card>
@endforeach
@endif
@stop


@section('script')
<script>
    var bsRangePickerSingle = $('.bs-rangepicker-single');
    if (bsRangePickerSingle.length) {
        bsRangePickerSingle.daterangepicker({
            singleDatePicker: true
            , opens: isRtl ? 'left' : 'right'
            , "locale": {
                "format": "DD/MM/YYYY"
            , }
        });
    }

</script>

@stop
