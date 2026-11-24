@props(['name', 'label', 'value' => null, 'rows' => 4, 'help' => null, 'required' => false, 'markdown' => false])

@php $id = 'f-'.$name; @endphp

<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>
    <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}"
        {{ $attributes->class(['form-control', 'js-markdown' => $markdown, 'is-invalid' => $errors->has($name)]) }}
        @required($required && ! $markdown)>{{ old($name, $value) }}</textarea>
    @if ($help)<div class="form-text">{{ $help }}</div>@elseif ($markdown)<div class="form-text">Use the toolbar for headings, lists and links. Click the eye icon to preview.</div>@endif
    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
