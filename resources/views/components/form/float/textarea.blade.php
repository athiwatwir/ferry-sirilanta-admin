@props([
'name'=>'description',
'help'=>'',
'label'=>'description',
'isrequire'=>false,'value'=>'',
'height'=>'100'
])

<div class="form-floating mb-2">
    <textarea class="form-control" id="{{ $name }}" name="{{ $name }}" style="height:{{ $height }}px">{{ $value }}</textarea>
    <label for="{{ $name }}" class="text-capitalize">{{ $label }} @if ($isrequire)
        <strong class="text-danger">*</strong>
        @endif</label>
    @if (!empty($help))
    <div id="{{ $name }}-help" class="form-text">{{ $help }}</div>
    @endif

</div>
