@extends('layouts.app')

@section('title', 'My profile')

@php $invalid = fn ($name, $bag = 'default') => ['is-invalid' => $errors->getBag($bag)->has($name)]; @endphp

@section('content')
    <x-page-header title="My Profile" :crumbs="['Member portal' => route('portal.dashboard'), 'Profile' => null]" />

    <section class="section-space">
        <div class="container">
            @include('portal._nav')

            <div class="row gutter-y-40">
                <div class="col-lg-8">
                    <div class="isoc-panel">
                        <h2 class="isoc-panel__title">Profile details</h2>
                        <p class="isoc-panel__lead">Help us connect you with the right working groups and opportunities.</p>

                        <form method="POST" action="{{ route('portal.profile.update') }}" class="form-one">
                            @csrf
                            @method('PUT')
                            <div class="form-one__group">
                                @foreach ([
                                    ['name', 'Full name *', 'text', 'name'],
                                    ['email', 'Email address *', 'email', 'email'],
                                    ['phone', 'Phone', 'tel', 'tel'],
                                    ['organisation', 'Organisation / school', 'text', 'organization'],
                                    ['job_title', 'Job title / role', 'text', 'organization-title'],
                                ] as [$name, $label, $type, $autocomplete])
                                    <div class="form-one__control">
                                        <label for="{{ $name }}">{{ $label }}</label>
                                        <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $user->{$name}) }}" autocomplete="{{ $autocomplete }}" @class($invalid($name))>
                                        @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                @endforeach
                                <div class="form-one__control">
                                    <label for="stakeholder_group">Stakeholder group</label>
                                    <select id="stakeholder_group" name="stakeholder_group" @class($invalid('stakeholder_group'))>
                                        <option value="">Choose one…</option>
                                        @foreach ($stakeholderGroups as $group)
                                            <option value="{{ $group }}" @selected(old('stakeholder_group', $user->stakeholder_group) === $group)>{{ $group }}</option>
                                        @endforeach
                                    </select>
                                    @error('stakeholder_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-one__control">
                                    <label for="parish">Parish</label>
                                    <select id="parish" name="parish" @class($invalid('parish'))>
                                        <option value="">Choose one…</option>
                                        @foreach ($parishes as $parish)
                                            <option value="{{ $parish }}" @selected(old('parish', $user->parish) === $parish)>{{ $parish }}</option>
                                        @endforeach
                                    </select>
                                    @error('parish')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-one__control form-one__control--full">
                                    <label for="bio">Short bio</label>
                                    <textarea id="bio" name="bio" rows="4" @class($invalid('bio'))>{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-one__control form-one__control--full">
                                    <label for="interests">Interests</label>
                                    <textarea id="interests" name="interests" rows="3" @class($invalid('interests'))>{{ old('interests', $user->interests) }}</textarea>
                                    @error('interests')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-one__control form-one__control--full">
                                    <button type="submit" class="truehelp-btn"><span>Save Profile</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="isoc-panel">
                        <h2 class="isoc-panel__title">Change password</h2>
                        <p class="isoc-panel__lead">Use at least 8 characters.</p>
                        <form method="POST" action="{{ route('portal.password.change') }}" class="form-one">
                            @csrf
                            @method('PUT')
                            <div class="form-one__group">
                            <div class="form-one__control form-one__control--full">
                                <label for="current_password">Current password</label>
                                <input type="password" id="current_password" name="current_password" autocomplete="current-password" required @class($invalid('current_password', 'password'))>
                                @error('current_password', 'password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <label for="new_password">New password</label>
                                <input type="password" id="new_password" name="password" autocomplete="new-password" required @class($invalid('password', 'password'))>
                                @error('password', 'password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-one__control form-one__control--full">
                                <label for="new_password_confirmation">Confirm new password</label>
                                <input type="password" id="new_password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                            </div>
                            <div class="form-one__control form-one__control--full"><button type="submit" class="truehelp-btn"><span>Update Password</span></button></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
