{{-- Template inner-page banner. $crumbs: [label => url|null]; Home is added automatically. --}}
@props(['title', 'text' => null, 'crumbs' => [], 'image' => null])

<section class="page-header">
    <div class="page-header__bg" style="background-image: url({{ $image ?? asset('assets/images/photos/roundtable.jpg') }});">
        <div class="page-header__shape-1"></div>
        <div class="page-header__shape-2"></div>
    </div>
    <div class="container">
        <div class="page-header__content">
            <h1 class="page-header__title">{{ $title }}</h1>
            @if ($text)
                <p class="page-header__text">{{ $text }}</p>
            @endif
            <nav aria-label="Breadcrumb">
                <ul class="truehelp-breadcrumb list-unstyled">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    @foreach ($crumbs as $label => $url)
                        <li>@if ($url)<a href="{{ $url }}">{{ $label }}</a>@else<span aria-current="page">{{ $label }}</span>@endif</li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>
</section>
