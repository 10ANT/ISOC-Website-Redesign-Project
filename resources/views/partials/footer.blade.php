@php $phoneHref = preg_replace('/[^0-9+]/', '', $settings['contact_phone'] ?? ''); @endphp

<footer class="main-footer section-space-top">
    <div class="main-footer__top">
        <div class="container">
            <div class="row gutter-y-40">
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="00ms">
                    <div class="footer-widget footer-widget--about">
                        <a href="{{ route('home') }}" class="footer-widget__logo">
                            <img src="{{ asset('assets/images/'.($isDark ?? false ? 'isoc-jamaica-logo-light.png' : 'isoc-jamaica-logo.png')) }}" width="220" height="88" alt="Internet Society Jamaica Chapter">
                        </a>
                        <p class="footer-widget__about-text">The Jamaica Chapter of the Internet Society. The Internet is for everyone.</p>
                        <ul class="footer-widget__links footer-widget__links--about">
                            <li><a href="{{ route('privacy') }}">Privacy policy</a></li>
                            <li><a href="{{ route('search') }}">Search</a></li>
                            <li><a href="{{ route('login') }}">Leader sign in</a></li>
                        </ul>
                        <a href="{{ route('membership') }}" class="footer-widget__btn truehelp-btn truehelp-btn--primary">
                            <span>Become a member</span>
                        </a>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="100ms">
                    <div class="footer-widget footer-widget--links">
                        <h2 class="footer-widget__title">Explore</h2>
                        <ul class="list-unstyled footer-widget__links">
                            <li><a href="{{ route('about') }}">About us</a></li>
                            <li><a href="{{ route('programmes.index') }}">Our work</a></li>
                            <li><a href="{{ route('events.index') }}">Events</a></li>
                            <li><a href="{{ route('news.index') }}">Latest news</a></li>
                            <li><a href="{{ route('resources.index') }}">Resources</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-5 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="200ms">
                    <div class="footer-widget footer-widget--contact">
                        <h2 class="footer-widget__title">Get In Touch!</h2>
                        <ul class="list-unstyled footer-widget__contact">
                            @if ($phoneHref)
                                <li>
                                    <span class="footer-widget__contact__icon"><i class="icon-phone-call"></i></span>
                                    <div class="footer-widget__contact__text">
                                        <a href="tel:{{ $phoneHref }}" class="footer-widget__contact__link">{{ $settings['contact_phone'] }}</a>
                                        <p class="footer-widget__contact__title">{{ $settings['office_hours'] ?? 'Call us' }}</p>
                                    </div>
                                </li>
                            @endif
                            @if (! empty($settings['contact_address']))
                                <li>
                                    <span class="footer-widget__contact__icon"><i class="icon-location-2"></i></span>
                                    <div class="footer-widget__contact__text">
                                        <span class="footer-widget__contact__link">{{ $settings['contact_address'] }}</span>
                                        <p class="footer-widget__contact__title">Our location</p>
                                    </div>
                                </li>
                            @endif
                            @if (! empty($settings['contact_email']))
                                <li>
                                    <span class="footer-widget__contact__icon"><i class="icon-email-2"></i></span>
                                    <div class="footer-widget__contact__text">
                                        <a href="mailto:{{ $settings['contact_email'] }}" class="footer-widget__contact__link">{{ $settings['contact_email'] }}</a>
                                        <p class="footer-widget__contact__title">Email us</p>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="300ms">
                    <div class="footer-widget footer-widget--newsletter">
                        <h2 class="footer-widget__title">Get Involved</h2>
                        <p class="footer-widget__newsletter-text">Have a question, an idea, or want to <br> volunteer? Send us a quick note.</p>
                        <form action="{{ route('contact') }}" method="GET" class="footer-widget__newsletter">
                            <label for="footer-email" class="visually-hidden">Your email address</label>
                            <input type="email" id="footer-email" name="email" placeholder="Email address">
                            <button type="submit">
                                <i class="icon-paper-plane"></i>
                                <span class="sr-only">Continue to the contact form</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-footer__bottom">
        <div class="container">
            <div class="main-footer__bottom__inner">
                <div class="row gutter-y-30 align-items-center">
                    <div class="col-lg-3 col-md-5">
                        <div class="main-footer__social">
                            @include('partials.social-links', ['iconSet' => 'truehelp'])
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-7">
                        <p class="main-footer__copyright">&copy; Copyright {{ now()->year }} Internet Society Jamaica Chapter.</p>
                    </div>
                    <div class="col-lg-3 text-center text-lg-end">
                        <a href="#main-content" class="main-footer__back-to-top truehelp-btn"><span>Back to Top</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
