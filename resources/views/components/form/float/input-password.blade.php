@props([
'name'=>'',
'help'=>'',
'label'=>'',
'isrequire'=>true,
'value'=>'',
'placeholder'=>''
])

<div class="form-floating mb-3">
    <input type="password" {{ $attributes->merge(['class' => 'form-control']) }} id="{{ $name }}" name="{{ $name }}" @required($isrequire) value="{{ $value }}" placeholder="{{ $placeholder }}" />
    <label for="{{ $name }}" class="text-capitalize">{{ $label }} @if ($isrequire)
        <strong class="text-danger">*</strong>
        @endif</label>
    @if (!empty($help))
    <div id="{{ $name }}-help" class="form-text">{{ $help }}</div>
    @endif

</div>
