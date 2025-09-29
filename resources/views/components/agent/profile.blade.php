@props(['path'=>''])

@if (empty($path))
<img src="{{ asset('images/agent.png') }}" alt="" {{ $attributes->merge(['class' => '']) }}>
@else
<img src="{{ asset($path) }}" alt="" {{ $attributes->merge(['class' => '']) }} width="110">
@endif
