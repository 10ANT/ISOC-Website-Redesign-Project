@extends('layouts.admin')

@section('title', $event->exists ? 'Edit event' : 'New event')

@section('content')
    <form method="POST" enctype="multipart/form-data"
        action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}">
        @csrf
        @if ($event->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <x-admin.input name="title" label="Title" :value="$event->title" required />
                        <x-admin.textarea name="summary" label="Summary" :value="$event->summary" rows="3" required
                            help="One or two sentences shown on event cards and in search results." />
                        <x-admin.textarea name="description" label="Description" :value="$event->description" markdown />
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Date &amp; location</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.input type="datetime-local" name="starts_at" label="Starts" :value="$event->starts_at" required />
                            </div>
                            <div class="col-md-6">
                                <x-admin.input type="datetime-local" name="ends_at" label="Ends" :value="$event->ends_at"
                                    help="Optional. Leave blank for events that end the same day." />
                            </div>
                        </div>
                        <x-admin.input name="venue" label="Venue" :value="$event->venue" help="Leave blank for online-only events." />
                        <x-admin.input type="url" name="online_url" label="Online meeting link" :value="$event->online_url" placeholder="https://" />
                        <x-admin.input type="url" name="registration_url" label="Registration link" :value="$event->registration_url" placeholder="https://" />
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">Publishing</div>
                    <div class="card-body">
                        <x-admin.checkbox name="is_published" label="Published" :checked="$event->is_published"
                            help="Unpublished events are hidden from the website." />
                        <x-admin.checkbox name="is_featured" label="Featured" :checked="$event->is_featured"
                            help="Highlight this event on the homepage." />
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary flex-fill"><i class="fa-solid fa-floppy-disk me-1"></i> Save</button>
                            <a href="{{ route('admin.events.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Details</div>
                    <div class="card-body">
                        <x-admin.select name="category_id" label="Category" :options="$categories->pluck('name', 'id')"
                            :value="$event->category_id" placeholder="— None —" />
                        <x-admin.select name="programme_id" label="Related programme" :options="$programmes->pluck('title', 'id')"
                            :value="$event->programme_id" placeholder="— None —" />
                        <x-admin.image :url="$event->image_url" label="Event image" />
                        <x-admin.input name="slug" label="URL slug" :value="$event->slug" help="Optional. Generated from the title if left blank." />
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
