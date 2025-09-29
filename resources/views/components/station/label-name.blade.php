@props([
'station'=>[],
])

<span>{{ $station['name_en'] }} [{{ $station['nickname'] }}] @if (!empty($station['piername_en']))
    <small>({{ $station['piername_en'] }})</small>
    @endif </span>
