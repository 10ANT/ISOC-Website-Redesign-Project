@extends('layouts.admin')

@section('title', 'Events')

@section('content')
    @php $past = request('when') === 'past'; @endphp

    <div class="page-actions">
        <form method="GET">
            @if ($past)<input type="hidden" name="when" value="past">@endif
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search titles…" aria-label="Search events">
            <button class="btn btn-outline-secondary">Search</button>
        </form>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> New event</a>
    </div>

    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a @class(['nav-link', 'active' => ! $past]) @if (! $past) aria-current="page" @endif
                href="{{ route('admin.events.index', array_filter(['q' => request('q')])) }}">Upcoming</a>
        </li>
        <li class="nav-item">
            <a @class(['nav-link', 'active' => $past]) @if ($past) aria-current="page" @endif
                href="{{ route('admin.events.index', array_filter(['when' => 'past', 'q' => request('q')])) }}">Past</a>
        </li>
    </ul>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                        <tr>
                            <td class="text-nowrap">
                                <div class="fw-semibold">{{ $event->starts_at->format('M j, Y') }}</div>
                                <div class="small text-muted">{{ $event->starts_at->format('g:i a') }}</div>
                            </td>
                            <td>
                                <a href="{{ route('admin.events.edit', $event) }}" class="title-link">{{ $event->title }}</a>
                                <div class="small text-muted">{{ $event->category?->name ?? 'Uncategorised' }}</div>
                            </td>
                            <td>{{ $event->location_label }}</td>
                            <td>
                                <span class="badge text-bg-light border">{{ $event->status->label() }}</span>
                                @if ($event->is_published)
                                    <span class="badge text-bg-success">Published</span>
                                @else
                                    <span class="badge text-bg-secondary">Hidden</span>
                                @endif
                                @if ($event->is_featured)
                                    <span class="badge text-bg-warning"><i class="fa-solid fa-star me-1"></i>Featured</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                @if ($event->is_published)
                                    <a href="{{ route('events.show', $event) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary" title="View"><i class="fa-solid fa-eye"></i><span class="visually-hidden">View</span></a>
                                @endif
                                <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen"></i><span class="visually-hidden">Edit</span></a>
                                <x-admin.delete-button :action="route('admin.events.destroy', $event)" confirm="Delete this event?" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-row">{{ $past ? 'No past events found.' : 'No upcoming events found.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $events->links() }}</div>
@endsection
