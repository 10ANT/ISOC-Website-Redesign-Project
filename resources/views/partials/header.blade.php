@php
    $user = auth()->user();
    $menu = [
        ['label' => 'Home', 'url' => route('home'), 'active' => request()->routeIs('home')],
        ['label' => 'About', 'url' => route('about'), 'active' => request()->routeIs('about', 'about.*', 'pages.show'), 'children' => [
            ['label' => 'Overview', 'url' => route('about')],
            ['label' => 'Who We Are', 'url' => route('pages.show', 'who-we-are')],
            ['label' => 'Our Mission', 'url' => route('pages.show', 'mission')],
            ['label' => 'Our History', 'url' => route('pages.show', 'history')],
            ['label' => 'Our Plans', 'url' => route('pages.show', 'our-plans')],
            ['label' => 'Leadership & Members', 'url' => route('about.leadership')],
        ]],
        ['label' => 'Our Work', 'url' => route('programmes.index'), 'active' => request()->routeIs('programmes.*'), 'children' => [
            ['label' => 'All programmes', 'url' => route('programmes.index')],
            ...$navProgrammes->map(fn ($p) => ['label' => $p->title, 'url' => route('programmes.show', $p)])->all(),
        ]],
        ['label' => 'Events', 'url' => route('events.index'), 'active' => request()->routeIs('events.*')],
        ['label' => 'News', 'url' => route('news.index'), 'active' => request()->routeIs('news.*')],
        ['label' => 'Resources', 'url' => route('resources.index'), 'active' => request()->routeIs('resources.*')],
        ['label' => 'Get Involved', 'url' => route('get-involved'), 'active' => request()->routeIs('get-involved', 'membership'), 'children' => [
            ['label' => 'Ways to get involved', 'url' => route('get-involved')],
            ['label' => 'Become a member', 'url' => route('membership')],
            ['label' => 'Volunteer', 'url' => route('get-involved').'#volunteer'],
        ]],
        ['label' => 'Contact', 'url' => route('contact'), 'active' => request()->routeIs('contact')],
    ];
    $phoneHref = preg_replace('/[^0-9+]/', '', $settings['contact_phone'] ?? '');
@endphp

<div class="topbar">
    <div class="container-fluid">
        <div class="topbar__inner">
            <ul class="topbar__info list-unstyled">
                @if (! empty($settings['contact_email']))
                    <li>
                        <span class="topbar__info__icon topbar__info__icon--email"><i class="icon-email"></i></span>
                        <a href="mailto:{{ $settings['contact_email'] }}">{{ $settings['contact_email'] }}</a>
                    </li>
                @endif
                @if (! empty($settings['contact_address']))
                    <li>
                        <span class="topbar__info__icon"><i class="icon-location"></i></span>
                        <span>{{ $settings['contact_address'] }}</span>
                    </li>
                @endif
            </ul>
            <div class="topbar__right">
                <ul class="topbar__pages list-unstyled">
                    @auth
                        @can('access-admin')
                            <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                        @endcan
                        <li><a href="{{ route('portal.dashboard') }}">Member portal</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="topbar__logout">Sign out</button>
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}">Member sign in</a></li>
                        <li><a href="{{ route('register') }}">Join</a></li>
                    @endauth
                </ul>
                @if ($phoneHref)
                    <ul class="topbar__contact list-unstyled">
                        <li>
                            <span class="topbar__contact__icon"><i class="icon-phone-call-2"></i></span>
                            Let’s talk <a href="tel:{{ $phoneHref }}">{{ $settings['contact_phone'] }}</a>
                        </li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<header class="main-header main-header--two sticky-header sticky-header--normal">
    <div class="container-fluid">
        <div class="main-header__inner">
            <div class="main-header__logo logo-retina">
                <a href="{{ route('home') }}" aria-label="ISOC Jamaica home">
                    <img src="{{ asset('assets/images/'.($isDark ?? false ? 'isoc-jamaica-logo-light.png' : 'isoc-jamaica-logo.png')) }}" alt="Internet Society Jamaica Chapter" width="180" height="72">
                </a>
            </div>
            <div class="main-header__right">
                <nav class="main-header__nav main-menu" aria-label="Main">
                    <ul class="main-menu__list">
                        @foreach ($menu as $item)
                            <li @class(['dropdown' => ! empty($item['children']), 'current' => $item['active']])>
                                <a href="{{ $item['url'] }}" @if ($item['active']) aria-current="page" @endif>{{ $item['label'] }}</a>
                                @if (! empty($item['children']))
                                    <ul>
                                        @foreach ($item['children'] as $child)
                                            <li @class(['current' => url()->current() === $child['url']])><a href="{{ $child['url'] }}">{{ $child['label'] }}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </nav>
                <div class="mobile-nav__btn mobile-nav__toggler" role="button" tabindex="0" aria-label="Open menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="main-header__link">
                    @auth
                        <a href="{{ route('portal.dashboard') }}" class="main-header__account" title="Member portal">{{ $user->initials() }}</a>
                    @else
                        <a href="{{ route('login') }}" class="main-header__user">
                            <i class="icon-user" aria-hidden="true"></i>
                            <span class="sr-only">Member sign in</span>
                        </a>
                    @endauth
                    <a href="#" class="main-header__search search-toggler">
                        <i class="icon-search" aria-hidden="true"></i>
                        <span class="sr-only">Search</span>
                    </a>
                    <a href="{{ route('membership') }}" class="main-header__btn truehelp-btn">
                        <span>Join Us</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
