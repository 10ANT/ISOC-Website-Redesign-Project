@extends('layouts.admin')

@section('title', $programme->exists ? 'Edit programme' : 'New programme')

@section('content')
    <form method="POST" enctype="multipart/form-data"
        action="{{ $programme->exists ? route('admin.programmes.update', $programme) : route('admin.programmes.store') }}">
        @csrf
        @if ($programme->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-admin.input name="title" label="Title" :value="$programme->title" required />
                        <x-admin.textarea name="summary" label="Summary" :value="$programme->summary" rows="3" required
                            help="One or two sentences shown on the Our Work page and programme cards." />
                        <x-admin.textarea name="body" label="Full description" :value="$programme->body" markdown />
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">Publishing</div>
                    <div class="card-body">
                        <x-admin.select name="status" label="Status"
                            :options="collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])"
                            :value="$programme->status" required />
                        <x-admin.checkbox name="is_featured" label="Featured" :checked="$programme->is_featured"
                            help="Show this programme on the homepage." />
                        <x-admin.input type="number" name="sort_order" label="Sort order" :value="$programme->sort_order ?? 0" min="0" max="999"
                            help="Lower numbers appear first." />
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary flex-fill"><i class="fa-solid fa-floppy-disk me-1"></i> Save</button>
                            <a href="{{ route('admin.programmes.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Details</div>
                    <div class="card-body">
                        <x-admin.input name="icon" label="Icon" :value="$programme->icon" placeholder="fa-shield-halved"
                            help="A Font Awesome icon name, such as fa-shield-halved or fa-tower-broadcast." />
                        <x-admin.image :url="$programme->image_url" label="Image" />
                        <x-admin.input name="slug" label="URL slug" :value="$programme->slug" help="Optional. Generated from the title if left blank." />
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
