{{-- Resource, shown in the template's accent "event card". --}}
@props(['resource', 'index' => 0])

@php
    $url = route('resources.show', $resource);
@endphp

<div class="event-card tone-{{ $index % 3 }}">
    <div class="event-card__content">
        <div class="meta-row">
            <span class="meta-label"><i class="fa-solid {{ $resource->type->icon() }}"></i> {{ $resource->type->label() }}</span>
            @if ($resource->is_members_only)
                <span class="meta-label meta-label--muted"><i class="fa-solid fa-lock"></i> Members</span>
            @endif
        </div>
        <h3 class="event-card__title"><a href="{{ $url }}">{{ $resource->title }}</a></h3>
        <p class="event-card__text">{{ Str::limit($resource->description, 130) }}</p>
        <p class="event-card__meta">
            @if ($resource->category)<span class="me-3"><i class="fa-solid fa-tag"></i>{{ $resource->category->name }}</span>@endif
            @if ($resource->published_on)<span><i class="fa-regular fa-calendar"></i>{{ $resource->published_on->format('M Y') }}</span>@endif
        </p>
        <a href="{{ $url }}" class="truehelp-btn truehelp-btn--white"><span>View Resource</span></a>
    </div>
    <img src="{{ asset('assets/images/shapes/event-shape-1-1.png') }}" alt="" class="event-card__shape">
</div>
