@props(['label' => 'Station', 'name' => '','selected'=>'','isrequire'=>true,'empty'=>'- ALL -' ])

<div class="form-floating mb-2">
    <select class="form-select" name="{{ $name }}" id="{{ $name }}" @required($isrequire)>
        <option value="" selected>{{ $empty }}</option>
        @foreach ($options as $index => $stations)
        <optgroup label="{{ $index }}">
            @foreach ($stations as $key => $station)
            <option value="{{ $key }}" @selected($selected==$key)>
                {{ $station }}
            </option>
            @endforeach
        </optgroup>
        @endforeach
    </select>

    <label for="{{ $name }}">{{ $label }} @if ($isrequire)
        <strong class="text-danger">*</strong>
        @endif</label>
</div>
