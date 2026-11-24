@extends('layouts.app')

@section('title', 'Choose a new password')

@section('content')
    <x-page-header title="Choose a New Password" :crumbs="['Sign in' => route('login'), 'Reset password' => null]" />

    <section class="section-space">
        <div class="container">
            <div class="auth-wrap">
                <div class="isoc-panel">
                    <form method="POST" action="{{ route('password.update') }}" class="form-one">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-one__group">
                            <div class="form-one__control form-one__control--full">
                                <label for="email">Email address</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" autocomplete="email" required @class(['is-invalid' => $errors->has('email')])>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <label for="password">New password</label>
                                <input type="password" id="password" name="password" autocomplete="new-password" required @class(['is-invalid' => $errors->has('password')])>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <label for="password_confirmation">Confirm new password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <button type="submit" class="truehelp-btn"><span>Reset Password</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
