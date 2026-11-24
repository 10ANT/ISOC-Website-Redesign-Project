@extends('layouts.app')

@section('title', 'Reset your password')

@section('content')
    <x-page-header title="Forgot Your Password?" :crumbs="['Sign in' => route('login'), 'Forgot password' => null]" />

    <section class="section-space">
        <div class="container">
            <div class="auth-wrap">
                <div class="isoc-panel">
                    <h2 class="isoc-panel__title">Reset your password</h2>
                    <p class="isoc-panel__lead">Enter the email address you registered with and we'll send you a link to choose a new password.</p>

                    <form method="POST" action="{{ route('password.email') }}" class="form-one">
                        @csrf
                        <div class="form-one__group">
                            <div class="form-one__control form-one__control--full">
                                <label for="email">Email address</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus @class(['is-invalid' => $errors->has('email')])>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <button type="submit" class="truehelp-btn"><span>Email Me a Reset Link</span></button>
                            </div>
                        </div>
                    </form>
                    <p class="mt-4 mb-0"><a href="{{ route('login') }}"><i class="fa-solid fa-arrow-left me-1"></i> Back to sign in</a></p>
                </div>
            </div>
        </div>
    </section>
@endsection
