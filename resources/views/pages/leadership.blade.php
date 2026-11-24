@extends('layouts.app')

@section('title', 'Leadership & members')
@section('description', 'Meet the ISOC Jamaica board, officers, founding members and Hall of Fame.')

@php
    $headings = [
        'board' => 'Board of Directors',
        'officer' => 'Chapter Officers',
        'founding' => 'The Founding Members',
        'hall-of-fame' => 'Hall of Fame',
    ];
@endphp

@section('content')
    <x-page-header title="Leadership & Members" text="The people who lead, founded and champion ISOC Jamaica."
        :crumbs="['About' => route('about'), 'Leadership' => null]" :image="asset('assets/images/photos/event-group.jpg')" />

    <section class="volunteers-page section-space">
        <div class="container">
            <nav class="isoc-tabs justify-content-center mb-5" aria-label="Jump to section">
                @foreach ($groups as $key => $group)
                    @if ($group['people']->isNotEmpty())
                        <a href="#{{ $key }}">{{ $group['label'] }}</a>
                    @endif
                @endforeach
            </nav>

            @foreach ($groups as $key => $group)
                @continue($group['people']->isEmpty())

                <div id="{{ $key }}" class="leadership-group {{ $loop->last ? '' : 'mb-5 pb-5' }}">
                    <x-sec-title :tagline="$group['label']" :title="$headings[$key] ?? $group['label']" center />

                    @if ($key === 'founding')
                        <p class="text-center mb-5">ISOC Jamaica was founded by members from across Jamaica's Internet community.</p>
                        <div class="founders">
                            @foreach ($group['people'] as $person)
                                <div class="founders__item wow fadeInUp" data-wow-duration="1000ms">
                                    <img src="{{ $person->image_url ?? asset('assets/images/person-placeholder.svg') }}" alt="" loading="lazy">
                                    <div>
                                        <strong>{{ $person->name }}</strong>
                                        @if ($person->sector)<small>{{ $person->sector }}</small>@endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="row gutter-y-30">
                            @foreach ($group['people'] as $person)
                                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ ($loop->index % 4) * 100 }}ms">
                                    <x-person-card :person="$person" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach

            @if ($groups->every(fn ($g) => $g['people']->isEmpty()))
                <x-empty-state icon="fa-users" title="Leadership details coming soon" />
            @endif
        </div>
    </section>
@endsection
