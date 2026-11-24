<div class="mobile-nav__wrapper">
    <div class="mobile-nav__overlay mobile-nav__toggler"></div>
    <div class="mobile-nav__content">
        <span class="mobile-nav__close mobile-nav__toggler" role="button" tabindex="0" aria-label="Close menu"><i class="icon-close"></i></span>
        <div class="logo-box">
            <a href="{{ route('home') }}" aria-label="ISOC Jamaica home">
                <img src="{{ asset('assets/images/isoc-jamaica-logo-light.png') }}" width="200" alt="Internet Society Jamaica Chapter">
            </a>
        </div>
        {{-- Filled with the main menu by truehelp.js --}}
        <div class="mobile-nav__container"></div>
        <ul class="mobile-nav__contact list-unstyled">
            @auth
                <li>
                    <span class="mobile-nav__contact__icon"><i class="fa fa-user"></i></span>
                    <a href="{{ route('portal.dashboard') }}">Member portal</a>
                </li>
            @else
                <li>
                    <span class="mobile-nav__contact__icon"><i class="fa fa-right-to-bracket"></i></span>
                    <a href="{{ route('login') }}">Member sign in</a>
                </li>
            @endauth
            @if (! empty($settings['contact_email']))
                <li>
                    <span class="mobile-nav__contact__icon"><i class="fa fa-envelope"></i></span>
                    <a href="mailto:{{ $settings['contact_email'] }}">{{ $settings['contact_email'] }}</a>
                </li>
            @endif
            @if (! empty($settings['contact_phone']))
                <li>
                    <span class="mobile-nav__contact__icon"><i class="fa fa-phone"></i></span>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['contact_phone']) }}">{{ $settings['contact_phone'] }}</a>
                </li>
            @endif
        </ul>
        <div class="mobile-nav__social">
            @include('partials.social-links')
        </div>
    </div>
</div>
