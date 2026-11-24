@extends('layouts.admin')

@section('title', 'Announcements')

@section('content')
    <div class="page-actions">
        <p class="text-muted mb-0">Announcements are shown to approved members in the member portal.</p>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> New announcement</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Expires</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($announcements as $announcement)
                        <tr>
                            <td>
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="title-link">{{ $announcement->title }}</a>
                                @if ($announcement->is_pinned)
                                    <span class="badge text-bg-warning ms-1"><i class="fa-solid fa-thumbtack me-1"></i>Pinned</span>
                                @endif
                                <div class="small text-muted">By {{ $announcement->author?->name ?? 'Unknown' }}</div>
                            </td>
                            <td>
                                @if (! $announcement->published_at)
                                    <span class="badge text-bg-secondary">Draft</span>
                                @elseif ($announcement->published_at->isFuture())
                                    <span class="badge text-bg-info">Scheduled</span>
                                    <div class="small text-muted">{{ $announcement->published_at->format('M j, Y g:i a') }}</div>
                                @elseif ($announcement->expires_at?->isPast())
                                    <span class="badge text-bg-dark">Expired</span>
                                    <div class="small text-muted">{{ $announcement->expires_at->format('M j, Y') }}</div>
                                @else
                                    <span class="badge text-bg-success">Published</span>
                                    <div class="small text-muted">{{ $announcement->published_at->format('M j, Y') }}</div>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $announcement->expires_at?->format('M j, Y g:i a') ?? 'Never' }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen"></i><span class="visually-hidden">Edit</span></a>
                                <x-admin.delete-button :action="route('admin.announcements.destroy', $announcement)" confirm="Delete this announcement?" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty-row">No announcements yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $announcements->links() }}</div>
@endsection
