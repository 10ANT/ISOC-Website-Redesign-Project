@extends('layouts.app')

@section('title', $programme->title)
@section('description', $programme->summary)

@section('content')
    <x-page-header :title="$programme->title" :text="$programme->summary"
        :crumbs="['Our Work' => route('programmes.index'), $programme->title => null]" :image="$programme->image_url" />

    <section class="blog-details section-space">
        <div class="container">
            <div class="row gutter-y-60">
                <div class="col-xl-8 col-lg-7">
                    <div class="blog-details__content">
                        @if ($programme->image_url)
                            <div class="blog-card blog-card--four">
                                <div class="blog-card__image wow fadeInUp" data-wow-duration="1500ms">
                                    <img src="{{ $programme->image_url }}" alt="{{ $programme->title }}">
                                </div>
                            </div>
                        @endif
                        <div class="blog-details__inner">
                            <div class="isoc-prose">@markdown($programme->body)</div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="sidebar">
                        <aside class="widget-area">
                            <div class="sidebar__single wow fadeInRight" data-wow-duration="1500ms">
                                <div class="event-details__organiser">
                                    <h3 class="event-details__organiser__title">At a glance</h3>
                                    <ul class="event-details__organiser__list list-unstyled">
                                        <li><span class="event-details__organiser__list__title">Status :</span><span class="event-details__organiser__list__text">{{ $programme->status->label() }}</span></li>
                                        <li><span class="event-details__organiser__list__title">Events :</span><span class="event-details__organiser__list__text">{{ $events->count() }} upcoming</span></li>
                                        <li><span class="event-details__organiser__list__title">Resources :</span><span class="event-details__organiser__list__text">{{ $resources->count() }}</span></li>
                                    </ul>
                                    <a href="{{ route('contact', ['type' => 'programmes']) }}" class="truehelp-btn"><span>Get Involved</span></a>
                                </div>
                            </div>

                            @if ($others->isNotEmpty())
                                <div class="sidebar__categories-wrapper sidebar__single wow fadeInRight" data-wow-duration="1500ms">
                                    <h4 class="sidebar__categories-title sidebar__title">Other Programmes</h4>
                                    <ul class="sidebar__categories list-unstyled">
                                        @foreach ($others as $other)
                                            <li><a href="{{ route('programmes.show', $other) }}">{{ $other->title }} <span><i class="fa-solid fa-arrow-right"></i></span></a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($events->isNotEmpty())
        <div class="events-two section-space">
            <div class="container">
                <x-sec-title tagline="Events" title="Upcoming events" center />
                <div class="row gutter-y-30">
                    @foreach ($events as $event)
                        <div class="col-xl-4 col-md-6"><x-event-card :event="$event" :index="$loop->index" /></div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if ($posts->isNotEmpty())
        <section class="section-space">
            <div class="container">
                <x-sec-title tagline="News" title="Latest updates" center />
                <div class="row gutter-y-30">
                    @foreach ($posts as $post)
                        <div class="col-xl-4 col-md-6"><x-post-card :post="$post" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($resources->isNotEmpty())
        <section class="section-space-bottom">
            <div class="container">
                <x-sec-title tagline="Resources" title="Related resources" center />
                <div class="row gutter-y-30">
                    @foreach ($resources as $resource)
                        <div class="col-xl-4 col-md-6"><x-resource-card :resource="$resource" :index="$loop->index" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
