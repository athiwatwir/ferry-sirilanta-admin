@props([
'schedule'=>[]
])

@if ($schedule->isopen=='Y')
<span class="badge bg-label-success d-flex mb-2">Open Route on&nbsp;
    <x-label-date :date="$schedule->startdate" /> -
    <x-label-date :date="$schedule->enddate" /></span>
@else
<span class="badge bg-label-warning">Close Route on&nbsp;
    <x-label-date :date="$schedule->startdate" /> -
    <x-label-date :date="$schedule->enddate" /></span>
@endif
