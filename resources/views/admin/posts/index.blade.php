@extends('layouts.admin')

@section('title', 'News')

@section('content')
    <div class="page-actions">
        <form method="GET">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search titles…" aria-label="Search news">
            <select name="status" class="form-select w-auto" aria-label="Filter by status" onchange="this.form.submit()">
                <option value="">All statuses</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
                <option value="draft" @selected(request('status') === 'draft')>Drafts</option>
            </select>
            <button class="btn btn-outline-secondary">Filter</button>
        </form>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> New post</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td>
                                <a href="{{ route('admin.posts.edit', $post) }}" class="title-link">{{ $post->title }}</a>
                                <div class="small text-muted">By {{ $post->author?->name ?? 'Unknown' }}</div>
                            </td>
                            <td>{{ $post->category?->name ?? '—' }}</td>
                            <td>
                                @if ($post->is_published)
                                    <span class="badge text-bg-success">Published</span>
                                    <div class="small text-muted">{{ $post->published_at->format('M j, Y') }}</div>
                                @elseif ($post->published_at)
                                    <span class="badge text-bg-info">Scheduled</span>
                                    <div class="small text-muted">{{ $post->published_at->format('M j, Y g:i a') }}</div>
                                @else
                                    <span class="badge text-bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $post->updated_at->diffForHumans() }}</td>
                            <td class="text-end text-nowrap">
                                @if ($post->is_published)
                                    <a href="{{ route('news.show', $post) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary" title="View"><i class="fa-solid fa-eye"></i><span class="visually-hidden">View</span></a>
                                @endif
                                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen"></i><span class="visually-hidden">Edit</span></a>
                                <x-admin.delete-button :action="route('admin.posts.destroy', $post)" confirm="Delete this news post?" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-row">No news posts found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $posts->links() }}</div>
@endsection
