@extends('layouts.admin')

@section('title', 'Resources')

@section('content')
    <div class="page-actions">
        <form method="GET">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search titles…" aria-label="Search resources">
            <select name="type" class="form-select w-auto" aria-label="Filter by type" onchange="this.form.submit()">
                <option value="">All types</option>
                @foreach ($types as $type)
                    <option value="{{ $type->value }}" @selected(request('type') === $type->value)>{{ $type->label() }}</option>
                @endforeach
            </select>
            <button class="btn btn-outline-secondary">Filter</button>
        </form>
        <a href="{{ route('admin.resources.create') }}" class="btn btn-primary"><i class="fa-solid fa-upload me-1"></i> Upload resource</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Topic</th>
                        <th>Downloads</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($resources as $resource)
                        <tr>
                            <td>
                                <a href="{{ route('admin.resources.edit', $resource) }}" class="title-link">{{ $resource->title }}</a>
                                <div class="small text-muted">
                                    @if ($resource->hasFile())
                                        <i class="fa-solid fa-paperclip me-1"></i>{{ $resource->file_extension }}@if ($resource->file_size) · {{ $resource->file_size_for_humans }}@endif
                                    @elseif ($resource->external_url)
                                        <i class="fa-solid fa-link me-1"></i>External link
                                    @endif
                                </div>
                            </td>
                            <td class="text-nowrap"><i class="fa-solid {{ $resource->type->icon() }} fa-fw text-muted me-1"></i>{{ $resource->type->label() }}</td>
                            <td>{{ $resource->category?->name ?? '—' }}</td>
                            <td>{{ number_format($resource->downloads) }}</td>
                            <td>
                                @if ($resource->is_published)
                                    <span class="badge text-bg-success">Published</span>
                                @else
                                    <span class="badge text-bg-secondary">Hidden</span>
                                @endif
                                @if ($resource->is_members_only)
                                    <span class="badge text-bg-info"><i class="fa-solid fa-lock me-1"></i>Members only</span>
                                @endif
                                @if ($resource->is_featured)
                                    <span class="badge text-bg-warning"><i class="fa-solid fa-star me-1"></i>Featured</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                @if ($resource->is_published)
                                    <a href="{{ route('resources.show', $resource) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary" title="View"><i class="fa-solid fa-eye"></i><span class="visually-hidden">View</span></a>
                                @endif
                                <a href="{{ route('admin.resources.edit', $resource) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen"></i><span class="visually-hidden">Edit</span></a>
                                <x-admin.delete-button :action="route('admin.resources.destroy', $resource)" confirm="Delete this resource and its file?" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-row">No resources found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $resources->links() }}</div>
@endsection
