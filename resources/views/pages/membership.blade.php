@extends('layouts.app')

@section('title', 'Membership')
@section('description', 'Become a member of ISOC Jamaica. Membership is free and open to everyone.')

@php
    $benefits = [
        ['fa-people-group', 'Community engagement', 'Connect with Jamaicans from every sector who care about the future of the Internet.'],
        ['fa-landmark', 'Policy development', "Help shape ISOC Jamaica's positions and responses to national consultations."],
        ['fa-globe', 'Internet governance', 'Take part in local, regional and global Internet governance processes.'],
    ];
    $faqs = [
        ['Who can join ISOC Jamaica?', 'Anyone who supports an open, globally connected, secure and trustworthy Internet: students, professionals, academics, civil society, government and business. You do not need to live in Jamaica to join the Chapter.'],
        ['Does membership cost anything?', 'No. Membership is free.'],
        ['How do I join?', 'Create a free account with the Internet Society, then choose the Jamaica Chapter. Chapter leaders approve new members through the Internet Society, usually within a few days.'],
        ['Why do I join through the Internet Society?', 'The Internet Society keeps every chapter\'s member records in one secure place, so your details are protected and you only need one account.'],
        ['What do members get?', 'Invitations to working groups, and early notice of events, training and fellowship opportunities.'],
    ];
    $joinUrl = $settings['isoc_global_url'] ?? 'https://www.internetsociety.org/become-a-member/';
@endphp

@section('content')
    <x-page-header title="Become a Member" :text="$settings['membership_intro'] ?? null" :crumbs="['Membership' => null]"
        :image="asset('assets/images/photos/hero-group.jpg')" />

    <section class="support-categories section-space">
        <div class="container">
            <x-sec-title tagline="Why Join" title="What membership includes" center />
            <div class="row gutter-y-30">
                @foreach ($benefits as [$icon, $title, $text])
                    <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ $loop->index * 100 }}ms">
                        <div @class(['support-categories__item', 'support-categories__item--card-bottom' => $loop->index === 1])>
                            <div class="support-categories__card tone-{{ $loop->index }}">
                                <span class="support-categories__card__icon"><i class="fa-solid {{ $icon }}"></i></span>
                                <h3 class="support-categories__card__title">{{ $title }}</h3>
                                <p class="support-categories__card__text">{{ $text }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="about-two section-space-bottom">
        <div class="container">
            <div class="row gutter-y-40 align-items-center">
                <div class="col-xl-6">
                    <x-sec-title tagline="Questions" title="Membership FAQs" />
                    <div class="faq-two__accordion">
                        <div class="truehelp-accrodion" data-grp-name="membership-faq">
                            @foreach ($faqs as [$question, $answer])
                                <div @class(['accrodion', 'active' => $loop->first])>
                                    <div class="accrodion-title">
                                        <h4>{{ $question }}<span class="accrodion-title__icon"></span></h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner"><p>{{ $answer }}</p></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="get-involved__card get-involved__card--3">
                        <div class="get-involved__card__content">
                            <span class="get-involved__card__icon"><i class="fa-solid fa-id-card"></i></span>
                            <p class="get-involved__card__border-text get-involved__card__border-text--1">Free membership</p>
                            <p class="get-involved__card__border-text get-involved__card__border-text--2">Takes two minutes</p>
                            <h3 class="get-involved__card__title"><a href="{{ $joinUrl }}" target="_blank" rel="noopener">Ready to join?</a></h3>
                            <p class="get-involved__card__text">1. Create a free Internet Society account. 2. Choose the Jamaica Chapter. 3. A Chapter leader approves you.</p>
                        </div>
                        <div class="get-involved__card__button">
                            <a href="{{ $joinUrl }}" target="_blank" rel="noopener" class="truehelp-btn truehelp-btn--primary"><span>Join via ISOC</span></a>
                        </div>
                    </div>
                    <p class="mt-4 mb-0">The button opens the Internet Society website in a new tab. Questions?
                        <a href="{{ route('contact', ['type' => 'membership']) }}">Ask us about membership</a>.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
