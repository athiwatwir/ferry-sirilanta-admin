@props([
'name'=>'isactive',
'label'=>'On',
'value'=>'N'
])
<label class="switch switch-lg switch-success">
    <input type="checkbox" class="switch-input" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}" @checked($value=='Y' ) />
    <span class="switch-toggle-slider">
        <span class="switch-on">
            <i class="icon-base ti tabler-check"></i>
        </span>
        <span class="switch-off">
            <i class="icon-base ti tabler-x"></i>
        </span>
    </span>
    <span class="switch-label">{{ $label }}</span>
</label>
