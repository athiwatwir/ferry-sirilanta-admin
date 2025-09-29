@props([
'name'=>'',
'help'=>'',
'label'=>'',
'isrequire'=>true,
'time'=>''
])

@php
if(!empty($time)){
$time = \Carbon\Carbon::parse($time)->format('H:i');
}else{
$time = '';
}
@endphp

<div class="mb-2 form-control-validation form-floating">
    <input class="form-control time-mask" name="{{ $name }}" id="{{ $name }}" type="text" placeholder="hh:mm" @required($isrequire) value="{{ $time }}" />
    <label for="{{ $name }}" class="form-label">{{ $label }} @if ($isrequire)
        <strong class="text-danger">*</strong>
        @endif</label>

    @if (!empty($help))
    <div id="{{ $name }}-help" class="form-text">{{ $help }}</div>
    @endif

</div>
