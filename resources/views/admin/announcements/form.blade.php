@extends('layouts.admin')

@section('title', $announcement->exists ? 'Edit announcement' : 'New announcement')

@section('content')
    <form method="POST"
        action="{{ $announcement->exists ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}">
        @csrf
        @if ($announcement->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-admin.input name="title" label="Title" :value="$announcement->title" required />
                        <x-admin.textarea name="body" label="Message" :value="$announcement->body" markdown required />
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">Publishing</div>
                    <div class="card-body">
                        <x-admin.select name="status" label="Status" :options="['draft' => 'Draft', 'published' => 'Published']"
                            :value="$announcement->published_at ? 'published' : 'draft'" />
                        <x-admin.input type="datetime-local" name="published_at" label="Publish date" :value="$announcement->published_at"
                            help="Leave blank to publish now. A future date schedules the announcement." />
                        <x-admin.input type="datetime-local" name="expires_at" label="Expiry date" :value="$announcement->expires_at"
                            help="Optional. The announcement is hidden from members after this date." />
                        <x-admin.checkbox name="is_pinned" label="Pin to top" :checked="$announcement->is_pinned"
                            help="Pinned announcements are listed first in the member portal." />
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary flex-fill"><i class="fa-solid fa-floppy-disk me-1"></i> Save</button>
                            <a href="{{ route('admin.announcements.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
