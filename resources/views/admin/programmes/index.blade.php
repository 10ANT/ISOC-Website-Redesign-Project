@extends('layouts.admin')

@section('title', 'Our Work')

@section('content')
    <div class="page-actions">
        <p class="text-muted mb-0">Programmes appear on the Our Work page in the order shown below.</p>
        <a href="{{ route('admin.programmes.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> New programme</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Programme</th>
                        <th>Status</th>
                        <th>Linked content</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($programmes as $programme)
                        <tr>
                            <td class="text-muted">{{ $programme->sort_order }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <i class="fa-solid {{ $programme->icon ?: 'fa-diagram-project' }} fa-fw fa-lg text-primary" aria-hidden="true"></i>
                                    <div>
                                        <a href="{{ route('admin.programmes.edit', $programme) }}" class="title-link">{{ $programme->title }}</a>
                                        <div class="small text-muted">{{ Str::limit($programme->summary, 90) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge text-bg-light border">{{ $programme->status->label() }}</span>
                                @if ($programme->is_featured)
                                    <span class="badge text-bg-warning"><i class="fa-solid fa-star me-1"></i>Featured</span>
                                @endif
                            </td>
                            <td class="small text-muted text-nowrap">
                                {{ $programme->events_count }} {{ Str::plural('event', $programme->events_count) }} ·
                                {{ $programme->posts_count }} {{ Str::plural('post', $programme->posts_count) }} ·
                                {{ $programme->resources_count }} {{ Str::plural('resource', $programme->resources_count) }}
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('programmes.show', $programme) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary" title="View"><i class="fa-solid fa-eye"></i><span class="visually-hidden">View</span></a>
                                <a href="{{ route('admin.programmes.edit', $programme) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen"></i><span class="visually-hidden">Edit</span></a>
                                <x-admin.delete-button :action="route('admin.programmes.destroy', $programme)" confirm="Delete this programme? Linked events, news and resources will be kept." />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-row">No programmes yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
