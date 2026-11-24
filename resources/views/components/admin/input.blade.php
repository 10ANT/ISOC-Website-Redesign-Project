@props(['name', 'label', 'value' => null, 'type' => 'text', 'help' => null, 'required' => false])

@php
    $id = 'f-'.str_replace(['[', ']', '.'], '-', $name);
    $current = old($name, $value instanceof \DateTimeInterface
        ? $value->format($type === 'date' ? 'Y-m-d' : 'Y-m-d\TH:i')
        : $value);
@endphp

<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ $current }}"
        {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}
        @required($required)>
    @if ($help)<div class="form-text">{{ $help }}</div>@endif
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
