{{-- Template "event card two": image card that reveals the summary on hover. --}}
@props(['event', 'index' => 0])

@php
    $tone = 'tone-'.($index % 3);
    $image = $event->image_url ?? asset('assets/images/photos/event-audience.jpg');
    $url = route('events.show', $event);
    $date = $event->starts_at->format('D, M j · g:i a');
    $isLive = $event->status === \App\Enums\EventStatus::Live;
@endphp

<div class="event-card-two">
    <div class="event-card-two__show {{ $tone }}">
        <div class="event-card-two__image">
            <img src="{{ $image }}" alt="{{ $event->title }}" loading="lazy">
        </div>
        <div class="event-card-two__content">
            <span class="event-card-two__date">{{ $isLive ? 'Happening now' : $date }}</span>
            <h3 class="event-card-two__title"><a href="{{ $url }}">{{ $event->title }}</a></h3>
            <div class="event-card-two__location">
                <span class="event-card-two__location__icon"><i class="{{ $event->venue ? 'icon-location' : 'fa-solid fa-video' }}"></i></span>
                <span class="event-card-two__location__link">{{ $event->location_label }}</span>
            </div>
            <a href="{{ $url }}" class="truehelp-btn truehelp-btn--white"><span>Event Details</span></a>
        </div>
        <img src="{{ asset('assets/images/shapes/event-shape-2-1.png') }}" alt="" class="event-card-two__shape">
    </div>
    <div class="event-card-two__hover {{ $tone }}">
        <div class="event-card-two__content">
            <span class="event-card-two__date">{{ $date }}</span>
            <h3 class="event-card-two__title"><a href="{{ $url }}">{{ $event->title }}</a></h3>
            <p class="event-card-two__text">{{ Str::limit($event->summary, 190) }}</p>
            <div class="event-card-two__location">
                <span class="event-card-two__location__icon"><i class="{{ $event->venue ? 'icon-location' : 'fa-solid fa-video' }}"></i></span>
                <span class="event-card-two__location__link">{{ $event->location_label }}</span>
            </div>
            <a href="{{ $url }}" class="truehelp-btn truehelp-btn--white"><span>Event Details</span></a>
        </div>
        <img src="{{ asset('assets/images/shapes/event-shape-2-1.png') }}" alt="" class="event-card-two__shape">
    </div>
</div>
