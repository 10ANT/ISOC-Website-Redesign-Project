@extends('layouts.admin')

@section('title', 'My profile')

@php $passwordErrors = $errors->getBag('password'); @endphp

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="card">
                @csrf
                @method('PUT')
                <div class="card-header">Your details</div>
                <div class="card-body">
                    <x-admin.input name="name" label="Full name" :value="$user->name" autocomplete="name" required />
                    <x-admin.input type="email" name="email" label="Email address" :value="$user->email" autocomplete="email" required
                        help="You sign in with this email." />
                    <x-admin.input type="tel" name="phone" label="Phone" :value="$user->phone" autocomplete="tel" />
                    <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Save details</button>
                </div>
            </form>
        </div>

        <div class="col-lg-5">
            <form method="POST" action="{{ route('admin.password.change') }}" class="card">
                @csrf
                @method('PUT')
                <div class="card-header">Change password</div>
                <div class="card-body">
                    @foreach ([
                        ['current_password', 'Current password', 'current-password'],
                        ['password', 'New password (at least 8 characters)', 'new-password'],
                        ['password_confirmation', 'Confirm new password', 'new-password'],
                    ] as [$name, $label, $autocomplete])
                        <div class="mb-3">
                            <label for="pw-{{ $name }}" class="form-label">{{ $label }}</label>
                            <input type="password" name="{{ $name }}" id="pw-{{ $name }}" autocomplete="{{ $autocomplete }}" required
                                @class(['form-control', 'is-invalid' => $passwordErrors->has($name)])>
                            @if ($passwordErrors->has($name))<div class="invalid-feedback">{{ $passwordErrors->first($name) }}</div>@endif
                        </div>
                    @endforeach
                    <button class="btn btn-primary"><i class="fa-solid fa-key me-1"></i> Update password</button>
                </div>
            </form>
        </div>
    </div>
@endsection
