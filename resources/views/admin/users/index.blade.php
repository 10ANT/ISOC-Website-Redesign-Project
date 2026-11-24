@extends('layouts.admin')

@section('title', 'Members & Users')

@section('content')
    @if ($pendingCount > 0 && request('status') !== 'pending')
        <div class="alert alert-warning d-flex flex-wrap justify-content-between align-items-center gap-2" role="status">
            <span><i class="fa-solid fa-user-clock me-1"></i> {{ $pendingCount }} membership {{ Str::plural('application', $pendingCount) }} waiting for review.</span>
            <a href="{{ route('admin.users.index', ['status' => 'pending']) }}" class="btn btn-sm btn-warning">Review applications</a>
        </div>
    @endif

    <div class="page-actions">
        <form method="GET">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Name, email or organisation…" aria-label="Search users">
            <select name="status" class="form-select w-auto" aria-label="Filter by status" onchange="this.form.submit()">
                <option value="">All statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
            <select name="role" class="form-select w-auto" aria-label="Filter by role" onchange="this.form.submit()">
                <option value="">All roles</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->value }}" @selected(request('role') === $role->value)>{{ $role->label() }}</option>
                @endforeach
            </select>
            <button class="btn btn-outline-secondary">Filter</button>
        </form>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Organisation</th>
                        <th>Stakeholder group</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <a href="{{ route('admin.users.edit', $user) }}" class="title-link">{{ $user->name }}</a>
                                <div class="small text-muted">{{ $user->email }}</div>
                            </td>
                            <td>{{ $user->organisation ?: '—' }}</td>
                            <td>{{ $user->stakeholder_group ?: '—' }}</td>
                            <td>{{ $user->role->label() }}</td>
                            <td><span class="badge text-bg-{{ $user->status->badge() }}">{{ $user->status->label() }}</span></td>
                            <td class="small text-muted text-nowrap">{{ $user->created_at->format('M j, Y') }}</td>
                            <td class="text-end text-nowrap">
                                @if ($user->status === \App\Enums\UserStatus::Pending)
                                    <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-success" title="Approve"><i class="fa-solid fa-check"></i><span class="visually-hidden">Approve</span></button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen"></i><span class="visually-hidden">Edit</span></a>
                                @unless ($user->is(auth()->user()))
                                    <x-admin.delete-button :action="route('admin.users.destroy', $user)" confirm="Delete this account? This cannot be undone." />
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty-row">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $users->links() }}</div>
@endsection
