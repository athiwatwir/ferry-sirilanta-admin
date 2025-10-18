@extends('layouts.default')

@section('content')
<x-card>
    <x-form :action="route('agent.store')">
        <input type="hidden" name="parent_agent_id" id="" value="{{ $agent->id }}">
        <input type="hidden" name="code" id="" value="{{ $agent->code }}">
        <input type="hidden" name="parent_agent_id" id="" value="{{ $agent->id }}">

        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.float.selection name="type" label="Type" :options="['AG'=>'Agent','BK'=>'Broker User']" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.input name="name" label="name" />
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
