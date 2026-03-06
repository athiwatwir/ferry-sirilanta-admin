@extends('layouts.default')

@section('content')
<x-card>
    <x-form :action="route('broker.storeUser', ['broker' => $broker])">
        <div class="row">
            <div class="col-12 col-lg-3">
                <x-form.float.input name="name" label="Full Name" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="mobile" label="Mobile" :isrequire="false" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="email" label="Email" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="password" label="Password" type="password" />
            </div>
        </div>
    </x-form>
</x-card>
@stop
