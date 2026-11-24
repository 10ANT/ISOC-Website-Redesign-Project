@extends('layouts.app')

@section('title', $past ? 'Past events' : 'Events')
@section('description', 'Webinars, workshops, training and forums from ISOC Jamaica.')

@section('content')
    <x-page-header title="Events" text="Webinars, workshops, training and forums on the issues shaping the Internet in Jamaica."
        :crumbs="['Events' => null]" :image="asset('assets/images/photos/event-audience.jpg')" />

    <section class="events-page section-space">
        <div class="container">
            <form method="GET" action="{{ route('events.index') }}" class="isoc-filter" role="search">
                <nav class="isoc-tabs" aria-label="Event timeframe">
                    <a href="{{ route('events.index', request()->except(['when', 'page'])) }}" @class(['active' => ! $past]) @if (! $past) aria-current="page" @endif>Upcoming</a>
                    <a href="{{ route('events.index', ['when' => 'past'] + request()->except(['when', 'page'])) }}" @class(['active' => $past]) @if ($past) aria-current="page" @endif>Past events</a>
                </nav>
                @if ($past)<input type="hidden" name="when" value="past">@endif
                <label for="event-search" class="visually-hidden">Search events</label>
                <input type="search" id="event-search" name="q" value="{{ $search }}" placeholder="Search events">
                <label for="event-category" class="visually-hidden">Category</label>
                <select name="category" id="event-category" onchange="this.form.submit()">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected($activeCategory === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="truehelp-btn"><span>Search</span></button>
            </form>

            @if ($events->isEmpty())
                <x-empty-state icon="fa-calendar" :title="$past ? 'No past events found' : 'No upcoming events found'"
                    text="Try a different search or category, or check back soon." />
            @else
                <div class="row gutter-y-30">
                    @foreach ($events as $event)
                        <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ ($loop->index % 3) * 100 }}ms">
                            <x-event-card :event="$event" :index="$loop->index" />
                        </div>
                    @endforeach
                </div>
                {{ $events->links('vendor.pagination.isoc') }}
            @endif
        </div>
    </section>
@endsection
