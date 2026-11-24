{{-- Template "blog card two". --}}
@props(['post'])

@php $url = route('news.show', $post); @endphp

<div class="blog-card blog-card--two">
    <a href="{{ $url }}" class="blog-card__image" tabindex="-1">
        <img src="{{ $post->image_url ?? asset('assets/images/photos/news-1.jpg') }}" alt="{{ $post->title }}" loading="lazy">
        @if ($post->category)
            <span class="blog-card__category">{{ $post->category->name }}</span>
        @endif
    </a>
    <div class="blog-card__content">
        <p class="blog-card__date">{{ strtoupper($post->published_at->format('d M')) }}</p>
        <ul class="list-unstyled blog-card__meta">
            <li>
                <span class="blog-card__meta__icon"><i class="icon-user-2"></i></span>
                {{ $post->author?->name ?? 'ISOC Jamaica' }}
            </li>
            <li>
                <span class="blog-card__meta__icon"><i class="fa-regular fa-clock"></i></span>
                {{ $post->reading_time }} min read
            </li>
        </ul>
        <h3 class="blog-card__title"><a href="{{ $url }}">{{ $post->title }}</a></h3>
        <p class="blog-card__text">{{ Str::limit($post->excerpt, 110) }}</p>
        <a href="{{ $url }}" class="truehelp-btn"><span>Read More</span></a>
    </div>
    <img src="{{ asset('assets/images/shapes/blog-shape-1-1.png') }}" alt="" class="blog-card__shape">
</div>
