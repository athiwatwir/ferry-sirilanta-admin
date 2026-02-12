@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-9 col-lg-6">

        </div>
        <div class="col-3 col-lg-6 text-end">
            <x-button.new :href="route('salesPartner.create', ['type' => 'agent'])" />
        </div>
    </div>
    <hr>
</x-card>
@endsection
