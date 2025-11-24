@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-6 col-lg-3">
        <a href="{{ route('report.booking') }}" class="btn btn-xl btn-outline-secondary w-100 p-8">Booking Report</a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="{{ route('report.account') }}" class="btn btn-xl btn-outline-secondary w-100 p-8">Account Report</a>
    </div>
</div>

@stop
