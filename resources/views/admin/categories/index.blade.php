@extends('layouts.admin')

@section('title', 'Categories')

{{-- Every inline form posts a field called "name", so a hidden "_form" marker
     tells us which form an error (and its old input) belongs to. --}}
@section('content')
    <p class="text-muted mb-4">Rename a category by editing its name and pressing Save. Deleting a category keeps its items, which become uncategorised.</p>

    <div class="row g-4">
        @foreach ($groups as $group)
            @php
                $type = $group['type'];
                $countKey = match ($type) {
                    \App\Enums\CategoryType::News => 'posts_count',
                    \App\Enums\CategoryType::Event => 'events_count',
                    \App\Enums\CategoryType::Resource => 'resources_count',
                };
                $itemLabel = match ($type) {
                    \App\Enums\CategoryType::News => 'post',
                    \App\Enums\CategoryType::Event => 'event',
                    \App\Enums\CategoryType::Resource => 'resource',
                };
                $addForm = 'add-'.$type->value;
            @endphp

            <div class="col-xl-4 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ $type->label() }}</span>
                        <span class="badge text-bg-light border">{{ $group['categories']->count() }}</span>
                    </div>

                    <ul class="list-group list-group-flush">
                        @forelse ($group['categories'] as $category)
                            @php
                                $formKey = 'edit-'.$category->id;
                                $hasError = old('_form') === $formKey && $errors->hasAny(['name', 'slug']);
                            @endphp
                            <li class="list-group-item">
                                <div class="d-flex align-items-start gap-2">
                                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex-fill">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="_form" value="{{ $formKey }}">
                                        <div class="input-group input-group-sm has-validation">
                                            <label for="cat-{{ $category->id }}" class="visually-hidden">Category name</label>
                                            <input type="text" name="name" id="cat-{{ $category->id }}" required maxlength="100"
                                                value="{{ old('_form') === $formKey ? old('name') : $category->name }}"
                                                @class(['form-control', 'is-invalid' => $hasError])>
                                            <button class="btn btn-outline-primary" title="Save name"><i class="fa-solid fa-check"></i><span class="visually-hidden">Save name</span></button>
                                            @if ($hasError)<div class="invalid-feedback">{{ $errors->first('name') ?: $errors->first('slug') }}</div>@endif
                                        </div>
                                    </form>
                                    <span class="small text-muted text-nowrap pt-1">{{ $category->{$countKey} }} {{ Str::plural($itemLabel, $category->{$countKey}) }}</span>
                                    <x-admin.delete-button :action="route('admin.categories.destroy', $category)"
                                        :confirm="'Delete the '.$category->name.' category? Its items will become uncategorised.'" />
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item empty-row">No categories yet.</li>
                        @endforelse
                    </ul>

                    <div class="card-body border-top">
                        <form method="POST" action="{{ route('admin.categories.store') }}">
                            @csrf
                            <input type="hidden" name="_form" value="{{ $addForm }}">
                            <input type="hidden" name="type" value="{{ $type->value }}">
                            @php $addError = old('_form') === $addForm && $errors->hasAny(['name', 'slug', 'type']); @endphp
                            <label for="new-{{ $type->value }}" class="form-label">Add category</label>
                            <div class="input-group has-validation">
                                <input type="text" name="name" id="new-{{ $type->value }}" required maxlength="100" placeholder="Category name"
                                    value="{{ old('_form') === $addForm ? old('name') : '' }}"
                                    @class(['form-control', 'is-invalid' => $addError])>
                                <button class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> Add</button>
                                @if ($addError)
                                    <div class="invalid-feedback">{{ $errors->first('name') ?: ($errors->first('slug') ?: $errors->first('type')) }}</div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
