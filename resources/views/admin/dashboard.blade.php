@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="page-actions">
        <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}.</p>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> New post</a>
            <a href="{{ route('admin.events.create') }}" class="btn btn-outline-primary"><i class="fa-solid fa-calendar-plus me-1"></i> New event</a>
            <a href="{{ route('admin.resources.create') }}" class="btn btn-outline-primary"><i class="fa-solid fa-upload me-1"></i> Upload resource</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach ($stats as $stat)
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route($stat['route']) }}" class="card stat-card">
                    <span class="stat-card__icon"><i class="fa-solid {{ $stat['icon'] }}"></i></span>
                    <span>
                        <span class="stat-card__value d-block">{{ number_format($stat['value']) }}</span>
                        <span class="stat-card__label">{{ $stat['label'] }}</span>
                    </span>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        Latest messages and requests
                        @if ($unreadCount > 0)
                            <span class="badge text-bg-primary ms-1">{{ $unreadCount }} unread</span>
                        @endif
                    </span>
                    <a href="{{ route('admin.messages.index') }}" class="small fw-normal">View all</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($messages as $message)
                        <li class="list-group-item">
                            <a href="{{ route('admin.messages.show', $message) }}" @class(['title-link text-decoration-none d-block', 'fw-bold' => ! $message->read_at])>
                                {{ $message->subject ?: $message->enquiryLabel() }}
                                @unless ($message->read_at)
                                    <span class="badge text-bg-primary ms-1">New</span>
                                @endunless
                            </a>
                            <div class="small text-muted">{{ $message->name }} · {{ $message->created_at->diffForHumans() }}</div>
                        </li>
                    @empty
                        <li class="list-group-item empty-row">No messages yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Upcoming events</span>
                    <a href="{{ route('admin.events.index') }}" class="small fw-normal">View all</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($events as $event)
                        <li class="list-group-item d-flex justify-content-between align-items-center gap-3">
                            <div>
                                <a href="{{ route('admin.events.edit', $event) }}" class="title-link text-decoration-none">{{ $event->title }}</a>
                                <div class="small text-muted">{{ $event->starts_at->format('D, M j, Y · g:i a') }} · {{ $event->location_label }}</div>
                            </div>
                            <span class="badge text-bg-light border">{{ $event->status->label() }}</span>
                        </li>
                    @empty
                        <li class="list-group-item empty-row">No upcoming events scheduled.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Draft news posts</span>
                    <a href="{{ route('admin.posts.index', ['status' => 'draft']) }}" class="small fw-normal">View all</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($drafts as $draft)
                        <li class="list-group-item">
                            <a href="{{ route('admin.posts.edit', $draft) }}" class="title-link text-decoration-none">{{ $draft->title }}</a>
                            <div class="small text-muted">Updated {{ $draft->updated_at->diffForHumans() }}</div>
                        </li>
                    @empty
                        <li class="list-group-item empty-row">No drafts in progress.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
