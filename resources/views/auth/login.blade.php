@extends('layouts.app')

@section('title', 'Leader sign in')

@section('content')
    <x-page-header title="Leader Sign In" text="For chapter leaders who update this website." :crumbs="['Sign in' => null]" />

    <section class="section-space">
        <div class="container">
            <div class="auth-wrap">
                <div class="isoc-panel">
                    <h2 class="isoc-panel__title">Welcome back</h2>
                    <p class="isoc-panel__lead">Looking to join the chapter? <a href="{{ route('membership') }}">See how to become a member</a>.</p>

                    <form method="POST" action="{{ route('login') }}" class="form-one">
                        @csrf
                        <div class="form-one__group">
                            <div class="form-one__control form-one__control--full">
                                <label for="email">Email address</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus @class(['is-invalid' => $errors->has('email')])>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" autocomplete="current-password" required @class(['is-invalid' => $errors->has('password')])>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control form-one__control--full d-flex justify-content-between flex-wrap gap-2">
                                <label class="form-one__check" for="remember">
                                    <input type="checkbox" id="remember" name="remember" value="1"> Keep me signed in
                                </label>
                                <a href="{{ route('password.request') }}">Forgot password?</a>
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <button type="submit" class="truehelp-btn"><span>Sign In</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
