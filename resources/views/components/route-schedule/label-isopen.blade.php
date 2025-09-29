@props([
'isopen'=>'Y'
])
@if ($isopen=='Y')
<span class="badge bg-label-success">Open Route</span>
@else
<span class="badge bg-label-warning">Close Route</span>
@endif
