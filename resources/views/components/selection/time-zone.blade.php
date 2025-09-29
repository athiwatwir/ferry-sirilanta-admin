@props([
'name'=>'','label'=>'','isrequire'=>true,'selected'=>'Asia/Bangkok'
])
<div class="form-floating mb-2">
    <select class="form-select" id="{{ $name }}" name="{{ $name }}" @required($isrequire)>
        @foreach ($timezones as $tz => $label)
        <option value="{{ $tz }}" {{ $selected == $tz ? 'selected' : '' }}>
            {{ $label }}
        </option>
        @endforeach
    </select>
    <label for="{{ $name }}">{{ $label }} @if ($isrequire)
        <strong class="text-danger">*</strong>
        @endif</label>

</div>
