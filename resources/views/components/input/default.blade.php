@props(['name'=>'','label'=>'','help'=>'','value'=>''])
<div class="mb-3 form-control-validation">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input type="text" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}" {{ $attributes->merge(['class' => 'form-control']) }} />
    @if (!empty($help))
    <div id="{{ $name }}-help" class="form-text"></div>
    @endif
</div>
