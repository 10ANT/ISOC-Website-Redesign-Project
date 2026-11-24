@extends('layouts.admin')

@section('title', 'Leadership & People')

@section('content')
    <div class="page-actions">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a href="{{ route('admin.people.index') }}" @class(['nav-link', 'active' => ! $activeGroup]) @if (! $activeGroup) aria-current="page" @endif>All</a>
            </li>
            @foreach ($groups as $group)
                <li class="nav-item">
                    <a href="{{ route('admin.people.index', ['group' => $group->value]) }}" @class(['nav-link', 'active' => $activeGroup === $group])
                        @if ($activeGroup === $group) aria-current="page" @endif>{{ $group->label() }}</a>
                </li>
            @endforeach
        </ul>
        <a href="{{ route('admin.people.create', array_filter(['group' => $activeGroup?->value])) }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> Add person</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Group</th>
                        <th>Sector</th>
                        <th>Order</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($people as $person)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if ($person->image_url)
                                        <img src="{{ $person->image_url }}" alt="" class="person-avatar">
                                    @else
                                        <span class="person-avatar" aria-hidden="true">{{ $person->initials() }}</span>
                                    @endif
                                    <a href="{{ route('admin.people.edit', $person) }}" class="title-link">{{ $person->name }}</a>
                                </div>
                            </td>
                            <td>{{ $person->position ?: '—' }}</td>
                            <td>{{ $person->group->label() }}</td>
                            <td>{{ $person->sector ?: '—' }}</td>
                            <td class="text-muted">{{ $person->sort_order }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.people.edit', $person) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen"></i><span class="visually-hidden">Edit</span></a>
                                <x-admin.delete-button :action="route('admin.people.destroy', $person)" confirm="Remove this person?" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-row">No people in this group yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $people->links() }}</div>
@endsection
