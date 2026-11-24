@extends('layouts.admin')

@section('title', $person->exists ? 'Edit person' : 'Add person')

@section('content')
    <form method="POST" enctype="multipart/form-data"
        action="{{ $person->exists ? route('admin.people.update', $person) : route('admin.people.store') }}">
        @csrf
        @if ($person->exists) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <x-admin.input name="name" label="Full name" :value="$person->name" required />
                        <div class="row">
                            <div class="col-md-6">
                                <x-admin.input name="position" label="Position" :value="$person->position" help="For example: President, Treasurer." />
                            </div>
                            <div class="col-md-6">
                                <x-admin.input name="sector" label="Sector" :value="$person->sector" help="For example: Academia, Private Sector." />
                            </div>
                        </div>
                        <x-admin.textarea name="bio" label="Biography" :value="$person->bio" rows="8" />
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">Publishing</div>
                    <div class="card-body">
                        <x-admin.select name="group" label="Group" :options="collect($groups)->mapWithKeys(fn ($g) => [$g->value => $g->label()])"
                            :value="$person->group" required />
                        <x-admin.input type="number" name="sort_order" label="Sort order" :value="$person->sort_order ?? 0" min="0" max="999"
                            help="Lower numbers appear first within the group." />
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary flex-fill"><i class="fa-solid fa-floppy-disk me-1"></i> Save</button>
                            <a href="{{ route('admin.people.index', array_filter(['group' => $person->group?->value])) }}" class="btn btn-light">Cancel</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Photo</div>
                    <div class="card-body">
                        <x-admin.image name="photo" label="Photo" :url="$person->image_url" help="A square head-and-shoulders photo works best. JPG, PNG or WebP, up to 4 MB." />
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
