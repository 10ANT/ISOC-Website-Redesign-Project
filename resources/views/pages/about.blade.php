@extends('layouts.app')

@section('title', 'About us')
@section('description', $pages['who-we-are']->summary ?? 'About the Internet Society Jamaica Chapter.')

@php
    $whoWeAre = $pages->get('who-we-are');
    $story = [
        'mission' => 'fa-bullseye',
        'history' => 'fa-clock-rotate-left',
        'our-plans' => 'fa-route',
    ];
@endphp

@section('content')
    <x-page-header title="About ISOC Jamaica" text="The Jamaican chapter of the Internet Society, working for an Internet that is open, secure and for everyone."
        :crumbs="['About' => null]" :image="asset('assets/images/photos/hero-group.jpg')" />

    <section class="about-two section-space">
        <div class="container">
            <div class="row gutter-y-40">
                <div class="col-xl-6 wow fadeInUp" data-wow-duration="1500ms">
                    <div class="about-two__image">
                        <img src="{{ $whoWeAre?->image_url ?? asset('assets/images/photos/about-team.jpg') }}" alt="ISOC community members working together" class="about-two__image__img">
                        <div class="total-fund">
                            <h3 class="total-fund__title">Focus areas</h3>
                            <h4 class="total-fund__text">{{ $programmes->where('is_featured', true)->count() ?: $programmes->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="about-two__content">
                        <x-sec-title tagline="Who we are" title="A local chapter of a global community" />
                        <p class="about-two__text wow fadeInUp" data-wow-duration="1500ms">{{ $whoWeAre?->summary }}</p>
                        <div class="about-two__inner">
                            <div class="about-two__inner__content">
                                @foreach ($programmes->take(2) as $programme)
                                    <div class="about-two__inner__item wow fadeInUp" data-wow-duration="1500ms">
                                        <span class="about-two__inner__icon"><i class="fa-solid {{ $programme->icon ?: 'fa-globe' }}"></i></span>
                                        <h4 class="about-two__inner__title">{{ $programme->title }}</h4>
                                    </div>
                                @endforeach
                            </div>
                            <div class="about-two__inner__image wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">
                                <img src="{{ asset('assets/images/photos/about-tower.jpg') }}" alt="">
                            </div>
                        </div>
                        <div class="about-two__bottom">
                            @if ($whoWeAre)
                                <div class="about-two__button wow fadeInUp" data-wow-duration="1500ms">
                                    <a href="{{ route('pages.show', $whoWeAre) }}" class="truehelp-btn"><span>Read More</span></a>
                                </div>
                            @endif
                            <p class="about-two__bottom__text wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">The Internet is for everyone. Our job is to help make that true in Jamaica.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="support-categories section-space-bottom">
        <div class="container">
            <x-sec-title tagline="Our Story" title="Mission, history & plans" center />
            <div class="row gutter-y-30">
                @foreach ($story as $slug => $icon)
                    @if ($page = $pages->get($slug))
                        <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ $loop->index * 100 }}ms">
                            <div @class(['support-categories__item', 'support-categories__item--card-bottom' => $loop->index === 1])>
                                <div class="support-categories__card tone-{{ $loop->index }}">
                                    <span class="support-categories__card__icon"><i class="fa-solid {{ $icon }}"></i></span>
                                    <h3 class="support-categories__card__title">{{ $page->title }}</h3>
                                    <p class="support-categories__card__text">{{ $page->summary }}</p>
                                    <a href="{{ route('pages.show', $page) }}" class="truehelp-btn truehelp-btn--white"><span>Read More</span></a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    @if ($board->isNotEmpty())
        <section class="volunteers-page section-space-bottom">
            <div class="container">
                <x-sec-title tagline="Leadership" title="Meet the board" center />
                <div class="row gutter-y-30">
                    @foreach ($board as $person)
                        <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ $loop->index * 100 }}ms">
                            <x-person-card :person="$person" />
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5">
                    <a href="{{ route('about.leadership') }}" class="truehelp-btn"><span>Board, Officers & Founders</span></a>
                </div>
            </div>
        </section>
    @endif

    @include('partials.join-band')
@endsection
