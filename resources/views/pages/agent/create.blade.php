@extends('layouts.default')

@section('content')
<x-card>
    <x-form :action="route('agent.store')">
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.float.input name="name" label="name" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="code" label="code" help="Only a-z and 0-9" class="inp-eng-num" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="prefix" label="Ticket Prefix" help="Only a-z and 0-9" class="inp-eng-num" />
            </div>
            <div class="col-12 col-lg-6">
                <label for="logo" class="form-label">Upload Logo</label>
                <input class="form-control form-control-lg" id="logo" name="logo" type="file" accept="image/*">
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.textarea />
            </div>
        </div>

    </x-form>
</x-card>

@stop

@section('script')
<script src="{{ asset('js/form-input.js') }}"></script>

@stop
