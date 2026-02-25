@extends('layouts.default')

@section('content')

@include('pages.booking.dashboard.broker')

<div class="row">

    <div class="col-12">
        <x-card>
            <div class="row mb-4">
                <div class="col-12">
                    <form method="GET" action="{{ route('broker.credit') }}" class="row g-2 align-items-center">
                        <div class="col-auto">
                            <label for="month" class="col-form-label">เดือน</label>
                        </div>
                        <div class="col-auto">
                            <input type="month" name="month" id="month" class="form-control" value="{{ date('Y-m', strtotime($calendarDate ?? 'now')) }}" onchange="this.form.submit()">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">แสดง</button>
                        </div>
                    </form>
                </div>
                <div class="col-12 mt-3">
                    <x-calendar-blank :date="$calendarDate ?? now()->format('Y-m-01')" :dailyTotals="$dailyTotals ?? []" />
                </div>
            </div>
        </x-card>
    </div>
</div>

@endsection
