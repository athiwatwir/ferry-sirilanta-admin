@props(['url'=>'','name'=>'A'])

@if (empty($url))
<div class="avatar me-2">
    <span class="avatar-initial rounded-circle bg-label-primary text-capitalize">{{ substr($name, 0, 1) }}</span>
</div>

@else
<div class="avatar me-2">
    <img src="{{ asset($url) }}" alt="Avatar" class="rounded-circle">
</div>

@endif
