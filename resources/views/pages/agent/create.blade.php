@extends('layouts.default')

@section('content')
<x-card>
    <x-form :action="route('agent.store')">
        <input type="hidden" name="parent_agent_id" id="" value="{{ $agentApi->id }}">
        <div class="row">
            <div class="col-12 col-lg-2">
                <x-form.float.selection name="type" label="Type" :options="['AG'=>'Agent','BK'=>'Broker User']" :default="$type" />
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.input name="name" label="name" />
            </div>
            <div class="col-12 col-lg-2">
                <x-form.float.input name="code" label="code" />
            </div>

            <div class="col-12 col-lg-6">
                <x-form.float.textarea />
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <h5>Login User</h5>
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.input name="name" label="name" />
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.input name="email" label="email" />
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.input name="password" label="password" type="password" />
            </div>
        </div>

    </x-form>
</x-card>

@stop

@section('script')
<script src="{{ asset('js/form-input.js') }}"></script>

@stop
