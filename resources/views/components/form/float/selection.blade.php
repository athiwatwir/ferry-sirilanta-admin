@props([
'name'=>'',
'help'=>'',
'label'=>'',
'default'=>'',
'isempty'=>false,
'options'=>[],
'isrequire'=>true
])

<div class="form-floating  mb-2">
    <select class="form-select" id="{{ $name }}" name="{{ $name }}" @required($isrequire)>
        @if ($isempty)
        <option value=""></option>
        @endif
        @foreach ($options as $key=> $item)
        <option value="{{ $key }}" @selected($key==$default)>{{ $item }}</option>
        @endforeach
    </select>
    <label for="{{ $name }}" class="text-capitalize">{{ $label }} @if ($isrequire)
        <strong class="text-danger">*</strong>
        @endif</label>
    @if (!empty($help))
    <div id="{{ $name }}-help" class="form-text">{{ $help }}</div>
    @endif
</div>
