@extends('layouts.app')

@section('title', $page->title)
@section('description', $page->summary)

@section('content')
    @php $isAbout = $aboutPages->contains('slug', $page->slug); @endphp

    <x-page-header :title="$page->title" :text="$page->summary"
        :crumbs="$isAbout ? ['About' => route('about'), $page->title => null] : [$page->title => null]" />

    <section class="blog-details section-space">
        <div class="container">
            <div class="row gutter-y-60">
                <div @class(['col-xl-8 col-lg-7' => $isAbout, 'col-xl-9 mx-auto' => ! $isAbout])>
                    <div class="blog-details__content">
                        @if ($page->image_url)
                            <div class="blog-card blog-card--four">
                                <div class="blog-card__image wow fadeInUp" data-wow-duration="1500ms">
                                    <img src="{{ $page->image_url }}" alt="{{ $page->title }}">
                                </div>
                            </div>
                        @endif
                        <div class="blog-details__inner">
                            <div class="isoc-prose">@markdown($page->body)</div>
                        </div>
                    </div>
                </div>

                @if ($isAbout)
                    <div class="col-xl-4 col-lg-5">
                        <div class="sidebar">
                            <aside class="widget-area">
                                <div class="sidebar__categories-wrapper sidebar__single wow fadeInRight" data-wow-duration="1500ms">
                                    <h4 class="sidebar__categories-title sidebar__title">About ISOC Jamaica</h4>
                                    <ul class="sidebar__categories list-unstyled">
                                        <li><a href="{{ route('about') }}">Overview <span><i class="fa-solid fa-arrow-right"></i></span></a></li>
                                        @foreach ($aboutPages as $item)
                                            <li @class(['active' => $item->slug === $page->slug])><a href="{{ route('pages.show', $item) }}">{{ $item->title }} <span><i class="fa-solid fa-arrow-right"></i></span></a></li>
                                        @endforeach
                                        <li><a href="{{ route('about.leadership') }}">Leadership & Members <span><i class="fa-solid fa-arrow-right"></i></span></a></li>
                                    </ul>
                                </div>
                                <div class="sidebar__single wow fadeInRight" data-wow-duration="1500ms">
                                    <div class="get-involved__card get-involved__card--3">
                                        <div class="get-involved__card__content">
                                            <span class="get-involved__card__icon"><i class="fa-solid fa-id-card"></i></span>
                                            <h3 class="get-involved__card__title"><a href="{{ route('membership') }}">Join the Chapter</a></h3>
                                            <p class="get-involved__card__text">Membership is free and open to everyone.</p>
                                        </div>
                                        <div class="get-involved__card__button">
                                            <a href="{{ route('membership') }}" class="truehelp-btn truehelp-btn--primary"><span>Become a member</span></a>
                                        </div>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
