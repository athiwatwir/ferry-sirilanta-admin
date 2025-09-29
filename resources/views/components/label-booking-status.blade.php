@props(['status'=>'DR'])
@php
$statuses = [
'DR' => ['text' => 'Non Approved', 'class' => 'text-secondary', 'icon'],
'CO' => ['text' => 'Approved', 'class' => 'text-success', 'icon'],
'VO' => ['text' => 'Canceled', 'class' => 'text-danger', 'icon'],
];
@endphp
<span class="{{ $statuses[$status]['class'] }}">{{ $statuses[$status]['text'] }}</span>
