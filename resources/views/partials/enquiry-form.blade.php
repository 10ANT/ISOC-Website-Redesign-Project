{{--
    Shared contact / volunteer form (template "form-one"), posting to contact.store.
    Pass $type (fixed enquiry type) or $enquiryTypes (visitor chooses).
--}}
@php
    $invalid = fn ($name) => ['is-invalid' => $errors->has($name)];
    $formId = $type ?? 'contact';
@endphp

<form method="POST" action="{{ route('contact.store') }}" class="form-one" novalidate>
    @csrf
    <div class="hp-field" aria-hidden="true">
        <label for="website-{{ $formId }}">Leave this field empty</label>
        <input type="text" name="website" id="website-{{ $formId }}" tabindex="-1" autocomplete="off">
    </div>

    <div class="form-one__group">
        <div class="form-one__control">
            <label for="{{ $formId }}-name">Full name *</label>
            <input type="text" id="{{ $formId }}-name" name="name" value="{{ old('name', auth()->user()?->name) }}" autocomplete="name" required @class($invalid('name'))>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-one__control">
            <label for="{{ $formId }}-email">Email address *</label>
            <input type="email" id="{{ $formId }}-email" name="email" value="{{ old('email', request('email', auth()->user()?->email)) }}" autocomplete="email" required @class($invalid('email'))>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-one__control">
            <label for="{{ $formId }}-phone">Phone</label>
            <input type="tel" id="{{ $formId }}-phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" @class($invalid('phone'))>
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        @isset($enquiryTypes)
            <div class="form-one__control">
                <label for="{{ $formId }}-type">What is this about? *</label>
                <select id="{{ $formId }}-type" name="enquiry_type" required @class($invalid('enquiry_type'))>
                    @foreach ($enquiryTypes as $value => $label)
                        <option value="{{ $value }}" @selected(old('enquiry_type', $selectedType ?? 'general') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('enquiry_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-one__control form-one__control--full">
                <label for="{{ $formId }}-subject">Subject</label>
                <input type="text" id="{{ $formId }}-subject" name="subject" value="{{ old('subject') }}" @class($invalid('subject'))>
                @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        @else
            <input type="hidden" name="enquiry_type" value="{{ $type }}">
            <input type="hidden" name="subject" value="{{ $subject ?? '' }}">
        @endisset

        <div class="form-one__control form-one__control--full">
            <label for="{{ $formId }}-message">{{ $messageLabel ?? 'Message *' }}</label>
            <textarea id="{{ $formId }}-message" name="message" required @class($invalid('message'))>{{ old('message') }}</textarea>
            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-one__control form-one__control--full">
            <p class="form-hint mb-3">We'll only use your details to respond to you. See our <a href="{{ route('privacy') }}">privacy policy</a>.</p>
            <button type="submit" class="truehelp-btn"><span>{{ $button ?? 'Send Message' }}</span></button>
        </div>
    </div>
</form>
