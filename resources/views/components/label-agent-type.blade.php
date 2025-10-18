@props(['type'=>'AG'])

@if ($type=='AG')
<span class="badge text-bg-primary">Agent</span>
@endif

@if ($type=='BK')
<span class="badge text-bg-secondary">Broker User</span>
@endif
