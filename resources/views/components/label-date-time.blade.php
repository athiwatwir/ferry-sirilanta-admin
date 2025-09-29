@props([
'datetime'=>now()
])

<span>{{ \Carbon\Carbon::parse($datetime)->format('d/m/Y H:i') }}</span>
