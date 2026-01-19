@extends('layouts.default')

@section('content')
<x-card>
    <x-form :action="route('infoImage.store')">
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.float.input name="name" label="Name" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.selection name="type" label="Type" :options="['route_map'=>'Route Map','time_table'=>'Time Table']" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="image" label="Image" type="file" :isrequire="true" accept="image/*" />
            </div>
            <div class="col-12">
                <x-form.float.textarea name="description" label="Description" />
            </div>


        </div>
    </x-form>
</x-card>

@stop
