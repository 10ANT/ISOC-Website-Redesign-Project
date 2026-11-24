@extends('layouts.app')

@section('title', 'News & updates')
@section('description', 'Announcements, stories and updates from ISOC Jamaica.')

@section('content')
    <x-page-header title="News & Updates" text="Chapter announcements, stories and insights, most recent first."
        :crumbs="['News' => null]" :image="asset('assets/images/photos/news-4.jpg')" />

    <section class="blog-page section-space">
        <div class="container">
            <div class="row gutter-y-60">
                <div class="col-xl-8 col-lg-7">
                    @if ($search || $activeCategory || $activeTag || request('year'))
                        <div class="isoc-notice">
                            <i class="fa-solid fa-filter"></i>
                            <div>
                                Showing {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}
                                @if ($search) matching “{{ $search }}”@endif
                                @if ($activeTag) tagged “{{ $activeTag }}”@endif
                                @if (request('year')) from {{ (int) request('year') }}@endif.
                                <a href="{{ route('news.index') }}" class="ms-1">Clear filters</a>
                            </div>
                        </div>
                    @endif

                    @if ($posts->isEmpty())
                        <x-empty-state icon="fa-newspaper" title="No news found" text="Try a different search or category." />
                    @else
                        <div class="row gutter-y-30">
                            @foreach ($posts as $post)
                                <div class="col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ ($loop->index % 2) * 100 }}ms">
                                    <x-post-card :post="$post" />
                                </div>
                            @endforeach
                        </div>
                        {{ $posts->links('vendor.pagination.isoc') }}
                    @endif
                </div>

                @include('news._sidebar')
            </div>
        </div>
    </section>
@endsection
