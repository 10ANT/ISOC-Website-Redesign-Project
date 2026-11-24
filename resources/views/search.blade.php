@extends('layouts.app')

@section('title', $term ? "Search: {$term}" : 'Search')

@php
    // Highlight the search term: split raw text on the term, escape every piece, wrap matches.
    $highlight = function (?string $text) use ($term) {
        $parts = preg_split('/('.preg_quote($term, '/').')/iu', Str::limit((string) $text, 220), -1, PREG_SPLIT_DELIM_CAPTURE);

        return collect($parts)->map(fn ($part, $i) => $i % 2 ? '<mark>'.e($part).'</mark>' : e($part))->implode('');
    };
@endphp

@section('content')
    <x-page-header title="Search" text="Find pages, programmes, events, news and resources across the site." :crumbs="['Search' => null]" />

    <section class="section-space">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <div class="sidebar__single mb-5">
                        <form method="GET" action="{{ route('search') }}" class="sidebar__search" role="search">
                            <label for="site-search" class="visually-hidden">Search the site</label>
                            <input type="search" id="site-search" name="q" value="{{ $term }}" placeholder="What are you looking for?" minlength="2">
                            <button type="submit" aria-label="Search" class="sidebar__search__icon"><i class="icon-search"></i></button>
                        </form>
                    </div>

                    @if (mb_strlen($term) < 2)
                        <x-empty-state icon="fa-magnifying-glass" title="Start typing to search" text="Enter at least two characters." />
                    @elseif ($total === 0)
                        <x-empty-state icon="fa-magnifying-glass" title="No results for “{{ $term }}”" text="Try different or fewer words, or browse events, news and resources." />
                    @else
                        <p class="mb-4">{{ $total }} {{ Str::plural('result', $total) }} for “{{ $term }}”</p>
                        @foreach ($results as $group => $items)
                            <section class="mb-5" aria-labelledby="group-{{ Str::slug($group) }}">
                                <h2 class="h3 mb-2" id="group-{{ Str::slug($group) }}">{{ $group }}</h2>
                                @foreach ($items as $item)
                                    <div class="search-result">
                                        @if ($item['meta'])<span class="meta-label mb-1">{{ $item['meta'] }}</span>@endif
                                        <h3><a href="{{ $item['url'] }}">{!! $highlight($item['title']) !!}</a></h3>
                                        <p>{!! $highlight($item['text']) !!}</p>
                                    </div>
                                @endforeach
                            </section>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
