@extends('layouts.app')

@section('title', 'Apply for membership')

@php $invalid = fn ($name) => ['is-invalid' => $errors->has($name)]; @endphp

@section('content')
    <x-page-header title="Apply for Membership" text="Membership is free. Once an administrator approves your application you'll have full access to the member portal."
        :crumbs="['Membership' => route('membership'), 'Apply' => null]" />

    <section class="section-space">
        <div class="container">
            <div class="auth-wrap auth-wrap--wide">
                <div class="isoc-panel">
                    <h2 class="isoc-panel__title">Your details</h2>
                    <p class="isoc-panel__lead">Already a member? <a href="{{ route('login') }}">Sign in</a>. Fields marked * are required.</p>

                    <form method="POST" action="{{ route('register') }}" class="form-one">
                        @csrf
                        <div class="form-one__group">
                            <div class="form-one__control">
                                <label for="name">Full name *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" autocomplete="name" required @class($invalid('name'))>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control">
                                <label for="email">Email address *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" required @class($invalid('email'))>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control">
                                <label for="phone">Phone</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" @class($invalid('phone'))>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control">
                                <label for="organisation">Organisation / school</label>
                                <input type="text" id="organisation" name="organisation" value="{{ old('organisation') }}" autocomplete="organization" @class($invalid('organisation'))>
                                @error('organisation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control">
                                <label for="stakeholder_group">Stakeholder group *</label>
                                <select id="stakeholder_group" name="stakeholder_group" required @class($invalid('stakeholder_group'))>
                                    <option value="">Choose one…</option>
                                    @foreach ($stakeholderGroups as $group)
                                        <option value="{{ $group }}" @selected(old('stakeholder_group') === $group)>{{ $group }}</option>
                                    @endforeach
                                </select>
                                @error('stakeholder_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control">
                                <label for="parish">Parish</label>
                                <select id="parish" name="parish" @class($invalid('parish'))>
                                    <option value="">Choose one…</option>
                                    @foreach ($parishes as $parish)
                                        <option value="{{ $parish }}" @selected(old('parish') === $parish)>{{ $parish }}</option>
                                    @endforeach
                                </select>
                                @error('parish')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <label for="interests">What Internet issues interest you?</label>
                                <textarea id="interests" name="interests" placeholder="e.g. cyber security, community networks, policy" @class($invalid('interests'))>{{ old('interests') }}</textarea>
                                @error('interests')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control">
                                <label for="password">Password *</label>
                                <input type="password" id="password" name="password" autocomplete="new-password" required @class($invalid('password'))>
                                <div class="form-hint">At least 8 characters.</div>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control">
                                <label for="password_confirmation">Confirm password *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <label class="form-one__check" for="terms">
                                    <input type="checkbox" id="terms" name="terms" value="1" @checked(old('terms')) required>
                                    <span>I agree that ISOC Jamaica may store my details to manage my membership, as described in the <a href="{{ route('privacy') }}" target="_blank">privacy policy</a>.</span>
                                </label>
                                @error('terms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <button type="submit" class="truehelp-btn"><span>Submit Application</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
