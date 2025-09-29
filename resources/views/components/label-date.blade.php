@props([
'date'=>now()
])

<span>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
