@extends('layouts.admin')

@section('title', $page->exists ? 'Edit page' : 'New page')

@section('content')
    <form method="POST" enctype="multipart/form-data"
        action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}">
        @csrf
        @if ($page->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-admin.input name="title" label="Title" :value="$page->title" required />
                        <x-admin.textarea name="summary" label="Summary" :value="$page->summary" rows="3"
                            help="Shown as the page introduction and in search results." />
                        <x-admin.textarea name="body" label="Page content" :value="$page->body" markdown />
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">Publishing</div>
                    <div class="card-body">
                        <x-admin.checkbox name="is_published" label="Published" :checked="$page->is_published"
                            help="Unpublished pages are hidden from the website." />
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary flex-fill"><i class="fa-solid fa-floppy-disk me-1"></i> Save</button>
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Details</div>
                    <div class="card-body">
                        <x-admin.image :url="$page->image_url" label="Header image" />
                        @if ($isProtected)
                            <x-admin.input name="slug" label="URL slug" :value="$page->slug" readonly
                                help="This is a core page, so its address cannot be changed." />
                        @else
                            <x-admin.input name="slug" label="URL slug" :value="$page->slug" help="Optional. Generated from the title if left blank." />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
