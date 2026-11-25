@extends('layouts.admin')

@section('title', $user->exists ? 'Edit account' : 'Add a leader')

@section('content')
    @php $editingSelf = $user->exists && $user->is(auth()->user()); @endphp

    <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
        @csrf
        @if ($user->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">Contact details</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.input name="name" label="Full name" :value="$user->name" required />
                            </div>
                            <div class="col-md-6">
                                <x-admin.input type="email" name="email" label="Email address" :value="$user->email" required
                                    help="They sign in with this email." />
                            </div>
                            <div class="col-md-6">
                                <x-admin.input type="tel" name="phone" label="Phone" :value="$user->phone" />
                            </div>
                            <div class="col-md-6">
                                <x-admin.select name="parish" label="Parish" :options="array_combine($parishes, $parishes)"
                                    :value="$user->parish" placeholder="— Not specified —" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">Professional details</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.input name="organisation" label="Organisation" :value="$user->organisation" />
                            </div>
                            <div class="col-md-6">
                                <x-admin.input name="job_title" label="Chapter role or job title" :value="$user->job_title" />
                            </div>
                        </div>
                        <x-admin.select name="stakeholder_group" label="Stakeholder group" :options="array_combine($stakeholderGroups, $stakeholderGroups)"
                            :value="$user->stakeholder_group" placeholder="— Not specified —" />
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">{{ $user->exists ? 'Reset password' : 'Password' }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.input type="password" name="password" label="Password" :required="! $user->exists" autocomplete="new-password"
                                    :help="$user->exists ? 'Leave blank to keep their current password.' : 'At least 8 characters. Share it with them privately.'" />
                            </div>
                            <div class="col-md-6">
                                <x-admin.input type="password" name="password_confirmation" label="Confirm password" :required="! $user->exists" autocomplete="new-password" />
                            </div>
                        </div>
                        @if ($user->exists)
                            <p class="small text-muted mb-0">Account added {{ $user->created_at->format('M j, Y') }}.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">Access</div>
                    <div class="card-body">
                        <x-admin.select name="role" label="Role" :options="collect($roles)->mapWithKeys(fn ($r) => [$r->value => $r->label()])"
                            :value="$user->role" required
                            :help="$editingSelf ? 'You cannot remove your own administrator role.' : 'Editors can update content. Administrators can also add and remove website users.'" />
                        <x-admin.select name="status" label="Status" :options="collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s === \App\Enums\UserStatus::Approved ? 'Active' : $s->label()])"
                            :value="$user->status" required
                            :help="$editingSelf ? 'You cannot suspend your own account.' : 'Suspended users cannot sign in.'" />
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary flex-fill"><i class="fa-solid fa-floppy-disk me-1"></i> {{ $user->exists ? 'Save' : 'Add leader' }}</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
