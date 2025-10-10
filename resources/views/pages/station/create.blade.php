@extends('layouts.default')

@section('content')

<x-card>
    <div class="row">
        <div class="col">
            <x-form :action="route('station.store')">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <x-form.float.selection name="section_id" label="Section" :options="$sections" />
                    </div>
                    <div class="col-12 col-lg-4">
                        <x-form.float.input name="name_en" label="Name EN" />
                    </div>
                    <div class="col-12 col-lg-4">
                        <x-form.float.input name="name_th" label="Name TH" />
                    </div>

                    <div class="col-12 col-lg-2">
                        <x-form.float.selection name="type" label="Type" :options="$types" />
                    </div>
                    <div class="col-12 col-lg-2">
                        <x-form.float.input name="nickname" label="Nickname" />
                    </div>
                    <div class="col-12 col-lg-4">
                        <x-form.float.input name="piername_en" label="Piername EN" :isrequire="false" />
                    </div>
                    <div class="col-12 col-lg-4">
                        <x-form.float.input name="piername_th" label="Piername TH" :isrequire="false" />
                    </div>
                    <div class="col-12 col-lg-4">
                        <x-form.float.textarea name="description" label="Description/Note" :isrequire="false" />
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <x-form.float.textarea name="master_from" label="Master From" height="200" />
                    </div>
                    <div class="col-12 col-lg-6">
                        <x-form.float.textarea name="master_to" label="Master To" height="200" />
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <h6><i class="icon-base ti tabler-bus"></i> Shuttle Bus transfer</h6>
                        <x-form.float.input name="shuttle_bus_price" label="Price" :isrequire="false" />
                        <x-form.float.textarea name="shuttle_bus_mouseover" label="Mouseover" />
                        <x-form.float.input name="shuttle_bus_text" label="Text" :isrequire="false" />
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <h6><i class="icon-base ti tabler-car"></i> Private Taxi transfer</h6>
                        <x-form.float.input name="private_taxi_price" label="Price" :isrequire="false" />
                        <x-form.float.textarea name="private_taxi_mouseover" label="Mouseover" />
                        <x-form.float.input name="private_taxi_text" label="Text" :isrequire="false" />
                    </div>

                    <div class="col-12 col-lg-6 mb-3">
                        <h6><i class="icon-base ti tabler-speedboat"></i> Longtail boat transfer</h6>
                        <x-form.float.input name="longtail_boat_price" label="Price" :isrequire="false" />
                        <x-form.float.textarea name="longtail_boat_mouseover" label="Mouseover" />
                        <x-form.float.input name="longtail_boat_text" label="Text" :isrequire="false" />
                    </div>
                </div>
            </x-form>
        </div>
    </div>
</x-card>


@stop
