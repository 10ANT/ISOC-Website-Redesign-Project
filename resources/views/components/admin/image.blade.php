{{-- Image upload with preview of the current image and a remove option. --}}
@props(['name' => 'image', 'label' => 'Image', 'url' => null, 'help' => 'JPG, PNG or WebP, up to 4 MB.'])

<div class="mb-3">
    <label for="f-{{ $name }}" class="form-label">{{ $label }}</label>
    @if ($url)
        <div class="d-flex align-items-start gap-3 mb-2">
            <img src="{{ $url }}" alt="Current {{ strtolower($label) }}" class="image-preview">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remove_{{ $name }}" value="1" id="f-remove-{{ $name }}">
                <label class="form-check-label" for="f-remove-{{ $name }}">Remove</label>
            </div>
        </div>
    @endif
    <input type="file" name="{{ $name }}" id="f-{{ $name }}" accept="image/jpeg,image/png,image/webp"
        @class(['form-control', 'is-invalid' => $errors->has($name)])>
    <div class="form-text">{{ $help }}</div>
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
