<div class="col-xl-4 col-lg-5">
    <div class="sidebar">
        <aside class="widget-area">
            <div class="sidebar__form sidebar__single wow fadeInRight" data-wow-duration="1500ms">
                <form action="{{ route('news.index') }}" method="GET" class="sidebar__search" role="search">
                    <label for="news-search" class="visually-hidden">Search news</label>
                    <input type="search" id="news-search" name="q" value="{{ $search ?? '' }}" placeholder="Search News">
                    <button type="submit" aria-label="Search" class="sidebar__search__icon"><i class="icon-search"></i></button>
                </form>
            </div>

            @if ($categories->isNotEmpty())
                <div class="sidebar__categories-wrapper sidebar__single wow fadeInRight" data-wow-duration="1500ms">
                    <h4 class="sidebar__categories-title sidebar__title">Categories</h4>
                    <ul class="sidebar__categories list-unstyled">
                        @foreach ($categories as $category)
                            <li @class(['active' => ($activeCategory ?? null) === $category->slug])>
                                <a href="{{ route('news.index', ['category' => $category->slug]) }}">
                                    {{ $category->name }}
                                    <span>{{ str_pad($category->posts_count, 2, '0', STR_PAD_LEFT) }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($recent->isNotEmpty())
                <div class="sidebar__posts-wrapper sidebar__single wow fadeInRight" data-wow-duration="1500ms">
                    <h4 class="sidebar__posts-title sidebar__title">Recent News</h4>
                    <ul class="sidebar__posts list-unstyled">
                        @foreach ($recent as $item)
                            <li class="sidebar__posts__item">
                                <div class="sidebar__posts__image">
                                    <img src="{{ $item->image_url ?? asset('assets/images/photos/news-1.jpg') }}" alt="">
                                </div>
                                <div class="sidebar__posts__content">
                                    <p class="sidebar__posts__meta">{{ $item->published_at->format('d M Y') }}</p>
                                    <h4 class="sidebar__posts__title"><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h4>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($years->count() > 1)
                <div class="sidebar__tags-wrapper sidebar__single wow fadeInRight" data-wow-duration="1500ms">
                    <h4 class="sidebar__tags-title sidebar__title">Archive</h4>
                    <div class="sidebar__tags">
                        @foreach ($years as $year)
                            <a href="{{ route('news.index', ['year' => $year]) }}" class="truehelp-btn truehelp-btn--white"><span>{{ $year }}</span></a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>
