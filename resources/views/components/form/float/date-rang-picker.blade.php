@props([
'name'=>'daterange',
'help'=>'',
'label'=>'Date Range',
'isrequire'=>true,
'value'=>''
])

<div class="form-floating mb-2">
    <input type="text" id="{{ $name }}" name="{{ $name }}" class="form-control bs-rangepicker" />
    <label for="{{ $name }}" class="text-capitalize">{{ $label }} @if ($isrequire)
        <strong class="text-danger">*</strong>
        @endif</label>
    @if (!empty($help))
    <div id="{{ $name }}-help" class="form-text">{{ $help }}</div>
    @endif

</div>
