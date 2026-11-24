@extends('layouts.admin')

@section('title', $resource->exists ? 'Edit resource' : 'New resource')

@section('content')
    <form method="POST" enctype="multipart/form-data"
        action="{{ $resource->exists ? route('admin.resources.update', $resource) : route('admin.resources.store') }}">
        @csrf
        @if ($resource->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <x-admin.input name="title" label="Title" :value="$resource->title" required />
                        <x-admin.textarea name="description" label="Description" :value="$resource->description" rows="6" required
                            help="A short overview of what the resource covers and who it is for." />
                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.select name="type" label="Type" :options="collect($types)->mapWithKeys(fn ($t) => [$t->value => $t->label()])"
                                    :value="$resource->type" required />
                            </div>
                            <div class="col-md-6">
                                <x-admin.input type="date" name="published_on" label="Publication date" :value="$resource->published_on" />
                            </div>
                        </div>
                        <x-admin.input name="author" label="Author" :value="$resource->author" help="Person or organisation that produced the resource." />
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">File or link</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="f-file" class="form-label">Upload file</label>
                            @if ($resource->hasFile())
                                <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                                    <span><i class="fa-solid fa-paperclip me-1"></i>{{ $resource->file_name }}@if ($resource->file_size) <span class="text-muted">({{ $resource->file_size_for_humans }})</span>@endif</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remove_file" value="1" id="f-remove-file" @checked(old('remove_file'))>
                                        <label class="form-check-label" for="f-remove-file">Remove file</label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="file" id="f-file"
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.csv,.txt,.zip"
                                @class(['form-control', 'is-invalid' => $errors->has('file')])>
                            <div class="form-text">PDF, Office documents, CSV, TXT or ZIP, up to 20 MB.@if ($resource->hasFile()) Uploading a new file replaces the current one.@endif</div>
                            @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <x-admin.input type="url" name="external_url" label="External link" :value="$resource->external_url" placeholder="https://"
                            help="Use instead of an upload when the resource is hosted elsewhere." />
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">Publishing</div>
                    <div class="card-body">
                        <x-admin.checkbox name="is_published" label="Published" :checked="$resource->is_published" />
                        <x-admin.checkbox name="is_members_only" label="Members only" :checked="$resource->is_members_only"
                            help="Only approved members can download it." />
                        <x-admin.checkbox name="is_featured" label="Featured" :checked="$resource->is_featured" />
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary flex-fill"><i class="fa-solid fa-floppy-disk me-1"></i> Save</button>
                            <a href="{{ route('admin.resources.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Details</div>
                    <div class="card-body">
                        <x-admin.select name="category_id" label="Topic" :options="$categories->pluck('name', 'id')"
                            :value="$resource->category_id" placeholder="— None —" />
                        <x-admin.select name="programme_id" label="Related programme" :options="$programmes->pluck('title', 'id')"
                            :value="$resource->programme_id" placeholder="— None —" />
                        <x-admin.input name="slug" label="URL slug" :value="$resource->slug" help="Optional. Generated from the title if left blank." />
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
