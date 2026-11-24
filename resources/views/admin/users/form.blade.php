@extends('layouts.admin')

@section('title', 'Edit account')

@section('content')
    @php $editingSelf = $user->is(auth()->user()); @endphp

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

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
                                <x-admin.input type="email" name="email" label="Email address" :value="$user->email" required />
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
                                <x-admin.input name="job_title" label="Job title" :value="$user->job_title" />
                            </div>
                        </div>
                        <x-admin.select name="stakeholder_group" label="Stakeholder group" :options="array_combine($stakeholderGroups, $stakeholderGroups)"
                            :value="$user->stakeholder_group" placeholder="— Not specified —" />
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Member profile</div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-3">Bio</dt>
                            <dd class="col-sm-9 message-body">{{ $user->bio ?: '—' }}</dd>
                            <dt class="col-sm-3">Interests</dt>
                            <dd class="col-sm-9">{{ $user->interests ?: '—' }}</dd>
                            <dt class="col-sm-3">Registered</dt>
                            <dd class="col-sm-9">{{ $user->created_at->format('M j, Y g:i a') }}</dd>
                            <dt class="col-sm-3">Approved</dt>
                            <dd class="col-sm-9 mb-0">{{ $user->approved_at?->format('M j, Y g:i a') ?? 'Not yet approved' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">Access</div>
                    <div class="card-body">
                        <x-admin.select name="role" label="Role" :options="collect($roles)->mapWithKeys(fn ($r) => [$r->value => $r->label()])"
                            :value="$user->role" required
                            :help="$editingSelf ? 'You cannot remove your own administrator role.' : 'Editors can manage content. Administrators can also manage users.'" />
                        <x-admin.select name="status" label="Status" :options="collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])"
                            :value="$user->status" required
                            :help="$editingSelf ? 'You cannot suspend or un-approve your own account.' : 'Only approved members can see members-only content.'" />
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary flex-fill"><i class="fa-solid fa-floppy-disk me-1"></i> Save</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
