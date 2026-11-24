@extends('layouts.admin')

@section('title', $post->exists ? 'Edit news post' : 'New news post')

@section('content')
    <form method="POST" enctype="multipart/form-data"
        action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}">
        @csrf
        @if ($post->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-admin.input name="title" label="Title" :value="$post->title" required />
                        <x-admin.textarea name="excerpt" label="Summary" :value="$post->excerpt" rows="3" required
                            help="One or two sentences shown on cards and in search results." />
                        <x-admin.textarea name="body" label="Article" :value="$post->body" markdown required />
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">Publishing</div>
                    <div class="card-body">
                        <x-admin.select name="status" label="Status" :options="['draft' => 'Draft', 'published' => 'Published']"
                            :value="$post->published_at ? 'published' : 'draft'" />
                        <x-admin.input type="datetime-local" name="published_at" label="Publish date" :value="$post->published_at"
                            help="Leave blank to publish now. A future date schedules the post." />
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary flex-fill"><i class="fa-solid fa-floppy-disk me-1"></i> Save</button>
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Details</div>
                    <div class="card-body">
                        <x-admin.select name="category_id" label="Category" :options="$categories->pluck('name', 'id')"
                            :value="$post->category_id" placeholder="— None —" />
                        <x-admin.select name="programme_id" label="Related programme" :options="$programmes->pluck('title', 'id')"
                            :value="$post->programme_id" placeholder="— None —" />
                        <x-admin.input name="tags" label="Tags" :value="implode(', ', $post->tags ?? [])" help="Separate tags with commas." />
                        <x-admin.image :url="$post->image_url" label="Featured image" />
                        <x-admin.input name="slug" label="URL slug" :value="$post->slug" help="Optional. Generated from the title if left blank." />
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
