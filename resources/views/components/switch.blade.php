@props([
'isactive'=>'Y',
'action'=>'',
'name'=>''
])
<label class="switch switch-success switch-square">
    <input type="checkbox" class="switch-input switch-button" name="" @checked($isactive=='Y' ) data-action="{{ $action }}" />
    <span class="switch-toggle-slider">
        <span class="switch-on">
            <i class="icon-base ti tabler-check"></i>
        </span>
        <span class="switch-off">
            <i class="icon-base ti tabler-x"></i>
        </span>
    </span>
    <span class="switch-label">On</span>
</label>
