@extends('layouts.default')

@section('content')
<x-card>
    <x-form :action="route('salesPartner.store')">
        <input type="hidden" name="type" id="" value="employee">
        <div class="row">

            <div class="col-12 col-lg-4">
                <x-form.float.input name="name" label="name" />
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.input name="code" label="code" />
            </div>



        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <h5>Login User</h5>
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.input name="user[name]" label="name" />
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.input name="user[email]" label="email" />
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.input name="user[password]" label="password" type="password" />
            </div>
        </div>

    </x-form>
</x-card>

@stop

@section('script')
<script src="{{ asset('js/form-input.js') }}"></script>

@stop
