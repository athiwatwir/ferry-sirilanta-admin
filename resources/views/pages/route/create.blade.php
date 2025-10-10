@extends('layouts.default')

@section('content')
<x-card>
    <x-form action="{{ route('route.store') }}" :backUrl="route('route.index')">
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-station.selection name="depart_station_id" label="Station From" />
            </div>
            <div class="col-12 col-lg-6">
                <x-station.selection name="dest_station_id" label="Station To" />
            </div>
        </div>
    </x-form>
</x-card>

@stop
