@props([
'isactive'=>"Y"
])

@if ($isactive =='Y')
<span class="badge bg-label-success" text-capitalized="">Active</span>
@else
<span class="badge bg-label-danger" text-capitalized="">Inactive</span>
@endif
