@extends('layouts.app')

@section('title', $post->title)
@section('description', $post->excerpt)
@section('og_image', $post->image_url ?? asset('assets/images/photos/news-1.jpg'))

@php $shareUrl = urlencode(route('news.show', $post)); $shareText = urlencode($post->title); @endphp

@section('content')
    <x-page-header :title="$post->title" :crumbs="['News' => route('news.index'), Str::limit($post->title, 40) => null]" />

    <section class="blog-details section-space">
        <div class="container">
            <div class="row gutter-y-60">
                <div class="col-xl-8 col-lg-7">
                    @unless ($post->is_published)
                        <div class="isoc-notice isoc-notice--warning"><i class="fa-solid fa-eye-slash"></i><div>This post is not published yet. Only staff can see this preview.</div></div>
                    @endunless

                    <div class="blog-details__content">
                        <div class="blog-card blog-card--four">
                            <div class="blog-card__image wow fadeInUp" data-wow-duration="1500ms">
                                <img src="{{ $post->image_url ?? asset('assets/images/photos/news-1.jpg') }}" alt="{{ $post->title }}">
                            </div>
                            <div class="blog-card__content">
                                <ul class="list-unstyled blog-card__meta wow fadeInUp" data-wow-duration="1500ms">
                                    <li>
                                        <span class="blog-card__meta__icon"><i class="icon-user-2"></i></span>
                                        {{ $post->author?->name ?? 'ISOC Jamaica' }}
                                    </li>
                                    @if ($post->published_at)
                                        <li>
                                            <span class="blog-card__meta__icon"><i class="fa-regular fa-calendar"></i></span>
                                            {{ $post->published_at->format('F j, Y') }}
                                        </li>
                                    @endif
                                    <li>
                                        <span class="blog-card__meta__icon"><i class="fa-regular fa-clock"></i></span>
                                        {{ $post->reading_time }} min read
                                    </li>
                                </ul>
                                <h3 class="blog-card__title wow fadeInUp" data-wow-duration="1500ms">{{ $post->title }}</h3>
                                <p class="blog-card__text lead-text">{{ $post->excerpt }}</p>
                            </div>
                        </div>

                        <div class="blog-details__inner">
                            <div class="isoc-prose">@markdown($post->body)</div>

                            <div class="blog-details__meta">
                                @if (! empty($post->tags))
                                    <div class="blog-details__tags">
                                        <h4 class="blog-details__meta__title">Tags:</h4>
                                        <div class="blog-details__tags__box">
                                            @foreach ($post->tags as $tag)
                                                <a href="{{ route('news.index', ['tag' => $tag]) }}" class="truehelp-btn truehelp-btn--white"><span>{{ $tag }}</span></a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <div class="blog-details__social">
                                    <h4 class="blog-details__meta__title">Share:</h4>
                                    <div class="social-links">
                                        <a href="https://x.com/intent/post?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" rel="noopener"><i class="icon-x-2" aria-hidden="true"></i><span class="sr-only">Share on X</span></a>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener"><i class="icon-facebook-3" aria-hidden="true"></i><span class="sr-only">Share on Facebook</span></a>
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener"><i class="icon-linkedin-2" aria-hidden="true"></i><span class="sr-only">Share on LinkedIn</span></a>
                                    </div>
                                </div>
                            </div>

                            @if ($post->programme)
                                <div class="isoc-notice mt-4">
                                    <i class="fa-solid fa-diagram-project"></i>
                                    <div>This story is part of our work on <a href="{{ route('programmes.show', $post->programme) }}">{{ $post->programme->title }}</a>.</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($related->isNotEmpty())
                        <h3 class="mt-5 mb-4">Related news</h3>
                        <div class="row gutter-y-30">
                            @foreach ($related as $item)
                                <div class="col-md-6"><x-post-card :post="$item" /></div>
                            @endforeach
                        </div>
                    @endif
                </div>

                @include('news._sidebar', ['search' => '', 'activeCategory' => $post->category?->slug])
            </div>
        </div>
    </section>
@endsection
