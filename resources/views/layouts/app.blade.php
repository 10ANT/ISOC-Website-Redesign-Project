<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $pageTitle = trim($__env->yieldContent('title'));
        $metaDescription = trim($__env->yieldContent('description')) ?: ($settings['hero_text'] ?? 'Internet Society Jamaica Chapter');
        $isDark = trim($__env->yieldContent('theme')) === 'dark';
    @endphp
    <title>{{ $pageTitle ? $pageTitle.' | ISOC Jamaica' : 'ISOC Jamaica | Internet Society Jamaica Chapter' }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="ISOC Jamaica">
    <meta property="og:title" content="{{ $pageTitle ?: 'ISOC Jamaica' }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/photos/hero-group.jpg'))">
    <meta name="theme-color" content="#0c1c2c">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/apple-touch-icon.png') }}">

    {{-- TrueHelp template fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Geologica:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/animate/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/jarallax/jarallax.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/jquery-magnific-popup/jquery.magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/truehelp-icons/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/truehelp.css') }}">
    @if ($isDark)
        <link rel="stylesheet" href="{{ asset('assets/css/truehelp-dark.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/isoc.css') }}">
    @stack('styles')
</head>
<body @class(['custom-cursor', 'page-dark' => $isDark])>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <div class="custom-cursor__cursor"></div>
    <div class="custom-cursor__cursor-two"></div>

    <div class="page-wrapper">
        @include('partials.header')

        <main id="main-content">
            @include('partials.flash')
            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    @include('partials.mobile-nav')

    <div class="search-popup">
        <div class="search-popup__overlay search-toggler"></div>
        <div class="search-popup__content">
            <form role="search" method="GET" class="search-popup__form" action="{{ route('search') }}">
                <label for="popup-search" class="visually-hidden">Search the site</label>
                <input type="search" id="popup-search" name="q" placeholder="Search events, news, resources…" minlength="2" required>
                <button type="submit" aria-label="Search" class="truehelp-btn"><i class="icon-search"></i></button>
            </form>
        </div>
    </div>

    <a href="#" class="scroll-top" aria-label="Back to top">
        <svg class="scroll-top__circle" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </a>

    <script src="{{ asset('assets/vendors/jquery/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jarallax/jarallax.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-appear/jquery.appear.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-magnific-popup/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/owl-carousel/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/wow/wow.js') }}"></script>
    <script src="{{ asset('assets/vendors/gsap/gsap.js') }}"></script>
    <script src="{{ asset('assets/vendors/gsap/scrolltrigger.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/gsap/splittext.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/gsap/truehelp-split.js') }}"></script>
    <script src="{{ asset('assets/js/truehelp.js') }}"></script>
    @stack('scripts')
</body>
</html>
