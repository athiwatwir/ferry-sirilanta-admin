@props(['label' => 'Station', 'name' => '','selected'=>'','isrequire'=>true,'empty'=>'- ALL -' ])


<div class="form-floating mb-2">
    <select class="form-select" name="{{ $name }}" id="{{ $name }}" @required($isrequire)>
        <option value="" selected>{{ $empty }}</option>
        @foreach ($sections as $index => $stations)


        <optgroup label="{{ $index }}">
            @foreach ($stations as $key => $station)
            <option value="{{ $station['id'] }}" @selected($selected==$station['id'])>
                {{ sprintf('[%s] %s', $station['nickname'], $station['name']) }}
            </option>
            @endforeach
        </optgroup>
        @endforeach
    </select>

    <label for="{{ $name }}">{{ $label }} @if ($isrequire)
        <strong class="text-danger">*</strong>
        @endif</label>
</div>
