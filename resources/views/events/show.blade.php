@extends('layouts.app')

@section('title', $event->title)
@section('description', $event->summary)
@section('og_image', $event->image_url ?? asset('assets/images/photos/event-audience.jpg'))

@php
    $status = $event->status;
    $isPast = $status === \App\Enums\EventStatus::Past;
    $calendarUrl = 'https://calendar.google.com/calendar/render?'.http_build_query([
        'action' => 'TEMPLATE',
        'text' => $event->title,
        'dates' => $event->starts_at->copy()->utc()->format('Ymd\THis\Z').'/'.$event->ends_at_or_default->copy()->utc()->format('Ymd\THis\Z'),
        'details' => $event->summary.' '.route('events.show', $event),
        'location' => $event->venue ?? $event->online_url,
    ]);
    $time = $event->starts_at->format('g:i a').($event->ends_at ? ' - '.$event->ends_at->format('g:i a') : '');
@endphp

@section('content')
    <x-page-header :title="$event->title" :crumbs="['Events' => route('events.index'), Str::limit($event->title, 40) => null]" />

    <section class="event-details section-space">
        <div class="container">
            <div class="row gutter-y-40">
                <div class="col-12">
                    <div class="event-details__thumbnail wow fadeInUp" data-wow-duration="1500ms">
                        <img src="{{ $event->image_url ?? asset('assets/images/photos/event-audience.jpg') }}" alt="{{ $event->title }}">
                        <p class="event-details__time">{{ $time }}</p>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="event-details__content">
                        <div class="event-details__location wow fadeInUp" data-wow-duration="1500ms">
                            <span class="event-details__location__icon"><i class="{{ $event->venue ? 'icon-location' : 'fa-solid fa-video' }}"></i></span>
                            @if ($event->venue)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($event->venue) }}" target="_blank" rel="noopener" class="event-details__location__link">{{ $event->location_label }}</a>
                            @else
                                <span class="event-details__location__link">{{ $event->location_label }}</span>
                            @endif
                        </div>
                        <h2 class="event-details__title wow fadeInUp" data-wow-duration="1500ms">{{ $event->title }}</h2>
                        <div class="event-details__content-group">
                            <div class="event-details__upcoming-event wow fadeInUp" data-wow-duration="1500ms">
                                <p class="event-details__upcoming-event__text">{{ $isPast ? 'Event date' : ($status === \App\Enums\EventStatus::Live ? 'Happening now' : 'Upcoming event date') }}</p>
                                <p class="event-details__upcoming-event__date">{{ $event->starts_at->format('d M') }}</p>
                            </div>
                            @if (! $isPast && $event->registration_url)
                                <div class="event-details__button wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">
                                    <a href="{{ $event->registration_url }}" target="_blank" rel="noopener" class="truehelp-btn"><span>Register Now</span></a>
                                </div>
                            @endif
                        </div>
                        <div class="event-details__about">
                            <h3 class="event-details__about__title wow fadeInUp" data-wow-duration="1500ms">About this event</h3>
                            <p class="lead-text">{{ $event->summary }}</p>
                            <div class="isoc-prose">@markdown($event->description)</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 wow fadeInRight" data-wow-duration="1500ms">
                    <div class="event-details__organiser">
                        <h3 class="event-details__organiser__title">Event details</h3>
                        <ul class="event-details__organiser__list list-unstyled">
                            <li>
                                <span class="event-details__organiser__list__title">Date :</span>
                                <span class="event-details__organiser__list__text">{{ $event->starts_at->format('l, F j, Y') }}</span>
                            </li>
                            <li>
                                <span class="event-details__organiser__list__title">Time :</span>
                                <span class="event-details__organiser__list__text">{{ $time }}</span>
                            </li>
                            <li>
                                <span class="event-details__organiser__list__title">Where :</span>
                                <span class="event-details__organiser__list__text">{{ $event->location_label }}</span>
                            </li>
                            @if ($event->category)
                                <li>
                                    <span class="event-details__organiser__list__title">Type :</span>
                                    <span class="event-details__organiser__list__text">{{ $event->category->name }}</span>
                                </li>
                            @endif
                            @if ($event->programme)
                                <li>
                                    <span class="event-details__organiser__list__title">Programme :</span>
                                    <a href="{{ route('programmes.show', $event->programme) }}" class="event-details__organiser__list__text">{{ $event->programme->title }}</a>
                                </li>
                            @endif
                            @if ($isPast && $event->online_url)
                                <li>
                                    <span class="event-details__organiser__list__title">Recording :</span>
                                    <a href="{{ $event->online_url }}" target="_blank" rel="noopener" class="event-details__organiser__list__text">Watch online</a>
                                </li>
                            @endif
                        </ul>
                        @unless ($isPast)
                            <a href="{{ $calendarUrl }}" target="_blank" rel="noopener" class="truehelp-btn"><span>Add to Calendar</span></a>
                        @endunless
                    </div>
                </div>
            </div>

            @if ($resources->isNotEmpty())
                <div class="mt-5 pt-4">
                    <x-sec-title tagline="Resources" title="Related resources" />
                    <div class="row gutter-y-30">
                        @foreach ($resources as $resource)
                            <div class="col-xl-4 col-md-6"><x-resource-card :resource="$resource" :index="$loop->index" /></div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if ($related->isNotEmpty())
        <div class="events-two section-space">
            <div class="container">
                <x-sec-title tagline="More Events" title="You might also like" center />
                <div class="row gutter-y-30">
                    @foreach ($related as $item)
                        <div class="col-xl-4 col-md-6"><x-event-card :event="$item" :index="$loop->index" /></div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection
