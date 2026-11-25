@extends('layouts.app')

@section('title', 'Get involved')
@section('description', 'Volunteer, attend events, apply for fellowships or become a member of ISOC Jamaica.')

@section('content')
    <x-page-header title="Get Involved" text="There are many ways to support an open, secure Internet in Jamaica, with or without formal membership."
        :crumbs="['Get Involved' => null]" :image="asset('assets/images/photos/event-group.jpg')" />

    <section class="get-involved get-involved--standalone section-space">
        <div class="container">
            <div class="row gutter-y-30">
                <div class="col-xxl-4 col-lg-6 wow fadeInUp" data-wow-duration="1500ms">
                    <div class="get-involved__card get-involved__card--1">
                        <div class="get-involved__card__bg">
                            <div class="get-involved__card__bg__inner" style="background-image: url({{ asset('assets/images/photos/youth.jpg') }});"></div>
                        </div>
                        <div class="get-involved__card__content">
                            <h3 class="get-involved__card__title"><a href="https://www.internetsociety.org/fellowships/" target="_blank" rel="noopener">Fellowships & competitions</a></h3>
                            <p class="get-involved__card__text">Internet Society fellowships, youth programmes and our own student competitions.</p>
                            <a href="https://www.internetsociety.org/fellowships/" target="_blank" rel="noopener" class="truehelp-btn"><span>Fellowships</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-lg-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">
                    <div class="get-involved__card get-involved__card--2">
                        <div class="get-involved__card__bg">
                            <div class="get-involved__card__bg__inner" style="background-image: url({{ asset('assets/images/photos/news-7.jpg') }});"></div>
                        </div>
                        <div class="get-involved__card__content">
                            <h3 class="get-involved__card__title"><a href="{{ route('contact', ['type' => 'programmes']) }}">Partner with us</a></h3>
                            <p class="get-involved__card__text">Organisations can partner with us on events, research and community projects.</p>
                            <a href="{{ route('contact', ['type' => 'programmes']) }}" class="truehelp-btn truehelp-btn--primary"><span>Talk to us</span></a>
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
                            <p class="get-involved__card__text">Free membership through the Internet Society, with working groups and early notice of events.</p>
                        </div>
                        <div class="get-involved__card__button">
                            <a href="{{ route('membership') }}" class="truehelp-btn truehelp-btn--primary"><span>Join</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="support-categories support-categories--requests section-space-bottom" id="requests">
        <div class="container">
            <x-sec-title tagline="Work with us" title="Send the Chapter a request" center />
            <div class="row gutter-y-30">
                @foreach ([
                    ['meeting', 'fa-handshake', 'Request a meeting', 'Meet with Chapter leaders about an idea, a partnership or a question.'],
                    ['event_request', 'fa-microphone', 'Request an event', 'Ask us to run a workshop or send a speaker to your school, group or event.'],
                    ['support', 'fa-hand-holding-heart', 'Support the Chapter', 'Offer funding, a venue, equipment or your skills to help our work.'],
                    ['project', 'fa-lightbulb', 'Suggest a project', 'Have an idea for an Internet project in your community? Tell us about it.'],
                ] as [$type, $icon, $title, $text])
                    <div class="col-xl-3 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ $loop->index * 100 }}ms">
                        <div class="support-categories__item">
                            <div class="support-categories__card tone-{{ $loop->index % 3 }}">
                                <span class="support-categories__card__icon"><i class="fa-solid {{ $icon }}"></i></span>
                                <h3 class="support-categories__card__title">{{ $title }}</h3>
                                <p class="support-categories__card__text">{{ $text }}</p>
                                <a href="{{ route('contact', ['type' => $type]) }}#request-form" class="truehelp-btn"><span>Send request</span></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if ($events->isNotEmpty())
        <div class="events-two section-space">
            <div class="container">
                <x-sec-title tagline="Attend" title="Upcoming events" center />
                <div class="row gutter-y-30">
                    @foreach ($events as $event)
                        <div class="col-xl-4 col-md-6"><x-event-card :event="$event" :index="$loop->index" /></div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <section class="contact section-space" id="volunteer">
        <div class="container">
            <div class="row gutter-y-40">
                <div class="col-lg-5">
                    <x-sec-title tagline="Volunteer" title="Lend a hand" />
                    <p class="lead-text">Tell us a bit about yourself and how you'd like to help. We'll be in touch when an opportunity matches your interests.</p>
                    <div class="isoc-prose">
                        <ul>
                            <li>Event support and registration</li>
                            <li>School and community outreach</li>
                            <li>Writing, design, photography and social media</li>
                            <li>Technical help for community network projects</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact__form">
                        <h3 class="contact__form__title">Volunteer Sign-up</h3>
                        @include('partials.enquiry-form', ['type' => 'volunteer', 'subject' => 'Volunteer sign-up', 'messageLabel' => 'How would you like to help? *', 'button' => 'Send'])
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
