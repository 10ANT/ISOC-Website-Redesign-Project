@extends('layouts.app')

@section('title', 'Contact us')
@section('description', 'Get in touch with ISOC Jamaica about membership, events, programmes, partnerships or media.')

@php $phoneHref = preg_replace('/[^0-9+]/', '', $settings['contact_phone'] ?? ''); @endphp

@section('content')
    <x-page-header title="Contact Us" text="Request a meeting or an event, offer support, suggest a project, or just ask a question. We'd love to hear from you."
        :crumbs="['Contact' => null]" :image="asset('assets/images/photos/hero-meeting.jpg')" />

    <section class="contact section-space">
        <div class="container">
            <div class="row gutter-y-40">
                <div class="col-lg-6 wow fadeInUp" data-wow-duration="1500ms">
                    <div class="contact__content">
                        <div class="contact__info">
                            <div class="contact__info__inner">
                                @if (! empty($settings['contact_address']))
                                    <div class="contact__info__item">
                                        <div class="contact__info__icon" style="--icon-size: 28px"><i class="icon-location-3"></i></div>
                                        <div class="contact__info__content">
                                            <h5 class="contact__info__title">Our Address</h5>
                                            <p class="contact__info__text contact__info__text--location">{{ $settings['contact_address'] }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if ($phoneHref)
                                    <div class="contact__info__item">
                                        <div class="contact__info__icon" style="--icon-size: 26px"><i class="icon-phone-call-2"></i></div>
                                        <div class="contact__info__content">
                                            <h5 class="contact__info__title">Contact</h5>
                                            <p class="contact__info__text contact__info__text--contact">Let’s talk &nbsp;<a href="tel:{{ $phoneHref }}">{{ $settings['contact_phone'] }}</a></p>
                                        </div>
                                    </div>
                                @endif
                                @if (! empty($settings['contact_email']))
                                    <div class="contact__info__item">
                                        <div class="contact__info__icon" style="--icon-size: 17px"><i class="icon-email-3"></i></div>
                                        <div class="contact__info__content">
                                            <h5 class="contact__info__title">Email</h5>
                                            <p class="contact__info__text contact__info__text--email"><a href="mailto:{{ $settings['contact_email'] }}">{{ $settings['contact_email'] }}</a></p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="contact-map">
                            <div class="google-map google-map__contact">
                                <iframe title="Map of Kingston, Jamaica" loading="lazy" class="map__contact"
                                    src="https://www.google.com/maps?q={{ urlencode($settings['contact_address'] ?? 'Kingston, Jamaica') }}&output=embed"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">
                    <div class="contact__form" id="request-form">
                        <h3 class="contact__form__title">Get In Touch With Us</h3>
                        @include('partials.enquiry-form')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
