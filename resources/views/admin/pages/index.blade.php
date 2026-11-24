@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
    <div class="page-actions">
        <p class="text-muted mb-0">Core pages are part of the site structure. They can be edited or unpublished, but not deleted.</p>
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> New page</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pages as $page)
                        @php
                            $isCore = in_array($page->slug, $protected, true);
                            $publicUrl = $page->slug === 'privacy-policy' ? route('privacy') : route('pages.show', $page);
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="title-link">{{ $page->title }}</a>
                                @if ($isCore)
                                    <span class="badge text-bg-light border ms-1"><i class="fa-solid fa-lock me-1"></i>Core page</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ parse_url($publicUrl, PHP_URL_PATH) }}</td>
                            <td>
                                @if ($page->is_published)
                                    <span class="badge text-bg-success">Published</span>
                                @else
                                    <span class="badge text-bg-secondary">Hidden</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $page->updated_at->diffForHumans() }}</td>
                            <td class="text-end text-nowrap">
                                @if ($page->is_published)
                                    <a href="{{ $publicUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary" title="View"><i class="fa-solid fa-eye"></i><span class="visually-hidden">View</span></a>
                                @endif
                                <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen"></i><span class="visually-hidden">Edit</span></a>
                                @unless ($isCore)
                                    <x-admin.delete-button :action="route('admin.pages.destroy', $page)" confirm="Delete this page?" />
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-row">No pages yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
