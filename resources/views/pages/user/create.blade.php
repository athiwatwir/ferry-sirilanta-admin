@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-12 col-lg-6 mx-auto">
        <x-card>
            <x-form :action="route('user.store')">

                <div class="row">
                    <div class="col-12">
                        <x-form.float.input name="name" label="name" />
                    </div>
                    <div class="col-12">
                        <x-form.float.input name="email" label="email" />
                    </div>
                    <div class="col-12">
                        <x-form.float.input-password name="password" label="password" />
                    </div>
                    <div class="col-12">
                        <x-form.float.selection name="role" label="Role" :options="['ADMIN'=>'Admin','SMASTER'=>'Station Master']" />
                    </div>
                    <div class="col-12">
                        <x-station.selection empty="" :isrequire="false" name="station_id" />
                    </div>
                </div>
            </x-form>

        </x-card>
    </div>
</div>

@stop
