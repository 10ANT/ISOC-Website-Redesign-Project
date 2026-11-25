@extends('layouts.app')

@section('theme', 'dark')

@php
    $photo = fn (string $name) => asset("assets/images/photos/{$name}.jpg");
    $owl = fn (int $count, array $responsive) => json_encode([
        // Slow enough to read each card; pauses while the visitor hovers.
        'items' => 1, 'margin' => 30, 'loop' => $count > 3, 'smartSpeed' => 1000, 'autoplay' => $count > 3,
        'autoplayTimeout' => 9000, 'autoplayHoverPause' => true,
        'nav' => false, 'dots' => false,
        'navText' => ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
        'responsive' => $responsive,
    ]);

    $slides = [
        [
            'tagline' => $settings['hero_tagline'] ?? 'Internet Society · Jamaica Chapter',
            'title' => $settings['hero_title'] ?? 'The Internet is for everyone.',
            'text' => $settings['hero_text'] ?? '',
            'button' => ['Become a Member', route('membership')],
            'images' => ['hero-group', 'hero-conversation'],
        ],
        [
            'tagline' => 'Our work',
            'title' => 'Connecting communities, protecting users.',
            'text' => 'Internet governance, community networks, IoT and cyber security, for every parish.',
            'button' => ['Explore Our Work', route('programmes.index')],
            'images' => ['community-networks', 'about-datacentre'],
        ],
    ];

    if ($nextEvent) {
        $slides[] = [
            'tagline' => 'Next event · '.$nextEvent->starts_at->format('F j'),
            'title' => $nextEvent->title,
            'text' => Str::limit($nextEvent->summary, 120),
            'button' => ['Event Details', route('events.show', $nextEvent)],
            'images' => ['event-audience', 'hero-meeting'],
        ];
    }
@endphp

@section('content')
    {{-- Hero slider (template "main slider two") --}}
    <section class="main-slider-two">
        <div class="main-slider-two__carousel truehelp-owl__carousel truehelp-owl__carousel--basic-nav owl-carousel owl-theme"
            data-owl-options='{"items": 1, "margin": 0, "animateIn": "fadeIn", "animateOut": "fadeOut", "loop": true, "smartSpeed": 700, "nav": true, "dots": true, "autoplay": true, "autoplayTimeout": 10000, "autoplayHoverPause": true, "navText": ["Prev", "Next"]}'>
            @foreach ($slides as $slide)
                <div class="main-slider-two__item">
                    <div class="main-slider-two__bg">
                        <div class="main-slider-two__bg__1" style="background-image: url({{ $photo('roundtable') }});"></div>
                        <div class="main-slider-two__bg__2" style="background-image: url({{ asset('assets/images/shapes/main-slider-bg-shape-2-1-dark.png') }});"></div>
                        <div class="main-slider-two__bg__shape-1"></div>
                        <div class="main-slider-two__bg__shape-2"></div>
                    </div>
                    <div class="slider-social">
                        @foreach (['social_facebook' => ['icon-facebook-2', 'Facebook'], 'social_x' => ['icon-x-2', 'X'], 'social_linkedin' => ['icon-linkedin', 'LinkedIn']] as $key => [$icon, $label])
                            @if (! empty($settings[$key]))
                                <a href="{{ $settings[$key] }}" target="_blank" rel="noopener">
                                    <span class="slider-social__icon"><i class="{{ $icon }}"></i></span>
                                    <span class="slider-social__text">{{ $label }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                    <div class="container">
                        <div class="row gutter-y-30">
                            <div class="col-xl-9">
                                <div class="main-slider-two__content">
                                    <div class="main-slider-two__top">
                                        <span class="main-slider-two__tagline">{{ $slide['tagline'] }}</span>
                                    </div>
                                    <h2 class="main-slider-two__title">
                                        <span class="main-slider-two__title__inner">{{ $slide['title'] }}</span>
                                    </h2>
                                    <div class="main-slider-two__description">
                                        <p class="main-slider-two__text">{{ $slide['text'] }}</p>
                                    </div>
                                    <div class="main-slider-two__button">
                                        <div class="main-slider-two__button__inner">
                                            <a href="{{ $slide['button'][1] }}" class="truehelp-btn truehelp-btn--white"><span>{{ $slide['button'][0] }}</span></a>
                                        </div>
                                    </div>
                                    <div class="slider-donors">
                                        <div class="slider-donors__inner">
                                            <div class="slider-donors__content">
                                                <div class="slider-donors__images">
                                                    <img src="{{ $photo('news-2') }}" alt="">
                                                    <img src="{{ $photo('hero-smile') }}" alt="">
                                                    <img src="{{ $photo('about-tower') }}" alt="">
                                                </div>
                                                <h3 class="slider-donors__amount count-box">
                                                    <span class="count-text" data-stop="{{ $foundingCount }}" data-speed="1500">{{ $foundingCount }}</span>
                                                    <span>+ founders</span>
                                                </h3>
                                            </div>
                                            <a href="{{ route('about.leadership') }}" class="slider-donors__btn" aria-label="Meet our founding members">
                                                <i class="icon-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="main-slider-two__image">
                        <div class="main-slider-two__image__1"><img src="{{ $photo($slide['images'][0]) }}" alt=""></div>
                        <div class="main-slider-two__image__2"><img src="{{ $photo($slide['images'][1]) }}" alt=""></div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Upcoming events (template "events two") --}}
    <div class="events-two section-space">
        <div class="container">
            <x-sec-title tagline="Our Events" title="What's coming up" center />
            @if ($events->isNotEmpty())
                <div class="events-two__carousel truehelp-owl__carousel truehelp-owl__carousel--basic-nav owl-carousel owl-theme"
                    data-owl-options='{!! $owl($events->count(), ['0' => ['items' => 1, 'nav' => true], '768' => ['items' => 2], '1200' => ['items' => 3]]) !!}'>
                    @foreach ($events as $event)
                        <div class="item"><x-event-card :event="$event" :index="$loop->index" /></div>
                    @endforeach
                </div>
            @else
                <x-empty-state icon="fa-calendar" title="No upcoming events right now" text="New events are added regularly." />
            @endif
            <div class="text-center mt-5">
                <a href="{{ route('events.index') }}" class="truehelp-btn"><span>All Events</span></a>
            </div>
        </div>
    </div>

    {{-- Focus areas (template "support categories") --}}
    @if ($programmes->isNotEmpty())
        <section class="support-categories section-space-top support-categories--home2">
            <div class="container">
                <div class="row gutter-y-30">
                    @foreach ($programmes as $programme)
                        <div class="col-xl-3 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ $loop->index * 100 }}ms">
                            <div @class(['support-categories__item', 'support-categories__item--card-bottom' => $loop->odd === false])>
                                <div class="support-categories__card tone-{{ $loop->index % 3 }}">
                                    <span class="support-categories__card__icon"><i class="fa-solid {{ $programme->icon ?: 'fa-globe' }}"></i></span>
                                    <h3 class="support-categories__card__title">{{ $programme->title }}</h3>
                                    <p class="support-categories__card__text">{{ Str::limit($programme->summary, 95) }}</p>
                                    <a href="{{ route('programmes.show', $programme) }}" class="truehelp-btn truehelp-btn--white"><span>Read More</span></a>
                                </div>
                                <p class="support-categories__item__text">{{ $programme->status->label() }} programme</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Introduction (template "about two") --}}
    <section class="about-two section-space">
        <div class="container">
            <div class="row gutter-y-40">
                <div class="col-xl-6 wow fadeInUp" data-wow-duration="1500ms">
                    <div class="about-two__image">
                        <img src="{{ $photo('about-team') }}" alt="ISOC community members working together" class="about-two__image__img">
                        <div class="total-fund">
                            <h3 class="total-fund__title">Founding members</h3>
                            <h4 class="total-fund__text">{{ $foundingCount }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="about-two__content">
                        <x-sec-title tagline="About ISOC Jamaica" :title="$settings['intro_title'] ?? 'A local voice for a global, open Internet'" />
                        <p class="about-two__text wow fadeInUp" data-wow-duration="1500ms">{{ Str::before($settings['intro_text'] ?? '', "\n") }}</p>
                        <div class="about-two__inner">
                            <div class="about-two__inner__content">
                                <div class="about-two__inner__item wow fadeInUp" data-wow-duration="1500ms">
                                    <span class="about-two__inner__icon"><i class="fa-solid fa-landmark"></i></span>
                                    <h4 class="about-two__inner__title">Policy & governance</h4>
                                </div>
                                <div class="about-two__inner__item wow fadeInUp" data-wow-duration="1500ms">
                                    <span class="about-two__inner__icon"><i class="fa-solid fa-people-group"></i></span>
                                    <h4 class="about-two__inner__title">Skills & community</h4>
                                </div>
                            </div>
                            <div class="about-two__inner__image wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">
                                <img src="{{ $photo('about-datacentre') }}" alt="">
                            </div>
                        </div>
                        <div class="about-two__bottom">
                            <div class="about-two__button wow fadeInUp" data-wow-duration="1500ms">
                                <a href="{{ route('about') }}" class="truehelp-btn"><span>About us</span></a>
                            </div>
                            <p class="about-two__bottom__text wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">Part of the global Internet Society, working for an open, secure Internet in Jamaica.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Membership band (template "supporters acquired") --}}
    <section class="supporters-acquired supporters-acquired--home-2">
        <div class="container">
            <div class="supporters-acquired__inner wow fadeInUp" data-wow-duration="1500ms">
                <div class="row gutter-y-30 align-items-center">
                    <div class="col-lg-9">
                        <div class="supporters-acquired__content">
                            <h2 class="supporters-acquired__title count-box">
                                <span class="count-text" data-stop="120" data-speed="1500">120</span>
                                <span>+ Chapters</span>
                            </h2>
                            <p class="supporters-acquired__text">of the Internet Society worldwide. Jamaica is one of them.</p>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="supporters-acquired__button">
                            <a href="{{ route('membership') }}" class="truehelp-btn"><span>Join Us</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Latest news (template "blog two") --}}
    @if ($posts->isNotEmpty())
        <section class="blog-two section-space-top section-space-bottom">
            <div class="container">
                <x-sec-title tagline="News & Updates" title="Latest from the Chapter" center />
                <div class="blog-two__carousel truehelp-owl__carousel truehelp-owl__carousel--basic-nav owl-carousel owl-theme"
                    data-owl-options='{!! $owl($posts->count(), ['0' => ['items' => 1, 'nav' => true], '992' => ['items' => 2], '1400' => ['items' => 3]]) !!}'>
                    @foreach ($posts as $post)
                        <div class="item"><x-post-card :post="$post" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Featured resources --}}
    @if ($resources->isNotEmpty())
        <section class="section-space-bottom">
            <div class="container">
                <x-sec-title tagline="Resource Library" title="Reports, guides & tools" center />
                <div class="row gutter-y-30">
                    @foreach ($resources as $resource)
                        <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ $loop->index * 100 }}ms">
                            <x-resource-card :resource="$resource" :index="$loop->index" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Get involved (template "get involved") --}}
    <section class="get-involved get-involved--standalone section-space-bottom">
        <div class="container">
            <div class="row gutter-y-30">
                <div class="col-xxl-4 col-lg-6 wow fadeInUp" data-wow-duration="1500ms">
                    <div class="get-involved__card get-involved__card--1">
                        <div class="get-involved__card__bg">
                            <div class="get-involved__card__bg__inner" style="background-image: url({{ $photo('event-group') }});"></div>
                        </div>
                        <div class="get-involved__card__content">
                            <h3 class="get-involved__card__title"><a href="{{ route('get-involved') }}#volunteer">Volunteer with us</a></h3>
                            <p class="get-involved__card__text">Help at events, run school outreach, or lend your skills in writing, design or tech.</p>
                            <a href="{{ route('get-involved') }}#volunteer" class="truehelp-btn"><span>Volunteer</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-lg-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">
                    <div class="get-involved__card get-involved__card--2">
                        <div class="get-involved__card__bg">
                            <div class="get-involved__card__bg__inner" style="background-image: url({{ $photo('event-talk') }});"></div>
                        </div>
                        <div class="get-involved__card__content">
                            <h3 class="get-involved__card__title"><a href="{{ route('events.index') }}">Attend an event</a></h3>
                            <p class="get-involved__card__text">Webinars, workshops and training on the issues shaping the Internet in Jamaica.</p>
                            <a href="{{ route('events.index') }}" class="truehelp-btn truehelp-btn--primary"><span>See events</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-lg-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="200ms">
                    <div class="get-involved__card get-involved__card--3">
                        <div class="get-involved__card__content">
                            <span class="get-involved__card__icon"><i class="fa-solid fa-id-card"></i></span>
                            <p class="get-involved__card__border-text get-involved__card__border-text--1">The Internet is for everyone</p>
                            <p class="get-involved__card__border-text get-involved__card__border-text--2">Free membership</p>
                            <h3 class="get-involved__card__title"><a href="{{ route('membership') }}">Become a member</a></h3>
                            <p class="get-involved__card__text">Take part in community engagement, policy development and Internet governance.</p>
                        </div>
                        <div class="get-involved__card__button">
                            <a href="{{ route('membership') }}" class="truehelp-btn truehelp-btn--primary"><span>Become a member</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
