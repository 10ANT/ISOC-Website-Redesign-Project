@extends('layouts.admin')

@section('title', 'Media Library')

@section('content')
    <div class="card mb-4">
        <div class="card-header">Upload files</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
                @csrf
                <label for="f-files" class="form-label">Choose up to 10 files</label>
                <div class="input-group has-validation">
                    <input type="file" name="files[]" id="f-files" multiple required
                        accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.docx,.pptx,.xlsx"
                        @class(['form-control', 'is-invalid' => $errors->has('files') || $errors->has('files.*')])>
                    <button class="btn btn-primary"><i class="fa-solid fa-upload me-1"></i> Upload</button>
                    @error('files')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @foreach ($errors->get('files.*') as $fileErrors)
                        @foreach ($fileErrors as $fileError)
                            <div class="invalid-feedback">{{ $fileError }}</div>
                        @endforeach
                    @endforeach
                </div>
                <div class="form-text">JPG, PNG, WebP, GIF, PDF, Word, PowerPoint or Excel files, up to 10 MB each. Copy a file's link to use it in content.</div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        @forelse ($items as $item)
            <div class="col-6 col-md-4 col-xl-3 col-xxl-2">
                <div class="media-tile">
                    <a href="{{ $item->url }}" target="_blank" rel="noopener" class="media-tile__preview" title="Open {{ $item->original_name }}">
                        @if ($item->is_image)
                            <img src="{{ $item->url }}" alt="{{ $item->alt_text ?: $item->original_name }}" loading="lazy">
                        @else
                            <i class="fa-solid fa-file-lines" aria-hidden="true"></i>
                            <span class="visually-hidden">Open {{ $item->original_name }}</span>
                        @endif
                    </a>
                    <div class="media-tile__body">
                        <span class="media-tile__name">{{ $item->original_name }}</span>
                        <span class="text-muted">{{ $item->size_for_humans }} · {{ $item->uploader?->name ?? 'Unknown' }}</span>
                        <span class="text-muted">{{ $item->created_at->format('M j, Y') }}</span>
                        <div class="d-flex gap-2 mt-auto">
                            <button type="button" class="btn btn-sm btn-outline-secondary flex-fill" data-copy="{{ $item->url }}"><i class="fa-solid fa-link"></i> Copy link</button>
                            <x-admin.delete-button :action="route('admin.media.destroy', $item)" confirm="Delete this file? Any content linking to it will show a broken link." />
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card"><div class="empty-row">No files uploaded yet.</div></div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">{{ $items->links() }}</div>
@endsection
