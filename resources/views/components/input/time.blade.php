@props(['name'=>'','label'=>'','help'=>'','value'=>''])
<div class="mb-3 form-control-validation">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input class="form-control time-mask" type="text" placeholder=" hh:mm" />
    @if (!empty($help))
    <div id="{{ $name }}-help" class="form-text"></div>
    @endif
</div>
