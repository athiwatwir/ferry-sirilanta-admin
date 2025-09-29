@props([
'time'=>now()
])

<span>{{ \Carbon\Carbon::parse($time)->format('H:i') }}</span>
