{{-- $options: [value => label]. --}}
@props(['name', 'label', 'options' => [], 'value' => null, 'placeholder' => null, 'help' => null, 'required' => false])

@php
    $id = 'f-'.$name;
    $current = (string) old($name, $value instanceof \BackedEnum ? $value->value : $value);
@endphp

<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>
    <select name="{{ $name }}" id="{{ $id }}" {{ $attributes->class(['form-select', 'is-invalid' => $errors->has($name)]) }} @required($required)>
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected($current === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    @if ($help)<div class="form-text">{{ $help }}</div>@endif
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
