@props(['name', 'label', 'checked' => false, 'help' => null])

<div class="form-check form-switch mb-3">
    <input type="hidden" name="{{ $name }}" value="0">
    <input class="form-check-input" type="checkbox" role="switch" name="{{ $name }}" id="f-{{ $name }}" value="1" @checked(old($name, $checked))>
    <label class="form-check-label fw-semibold" for="f-{{ $name }}">{{ $label }}</label>
    @if ($help)<div class="form-text mt-0">{{ $help }}</div>@endif
    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
