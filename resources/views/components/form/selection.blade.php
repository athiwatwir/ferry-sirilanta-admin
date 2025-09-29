@props([
'name'=>'',
'label'=>'',
'options'=>[]
])
<div class="mb-2">
    @if (!empty($label))
    <label for="{{ $name }}" class="form-label text-capitalize">{{ $label }}</label>
    @endif

    <select {{ $attributes->merge(['class' => 'form-select']) }} id="{{ $name }}" name="{{ $name }}">
        @foreach ($options as $index=>$text)
        <option value="{{ $index }}">{{ $text }}</option>
        @endforeach
    </select>
</div>
