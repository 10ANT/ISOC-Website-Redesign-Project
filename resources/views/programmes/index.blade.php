@extends('layouts.app')

@section('title', 'Our work')
@section('description', 'ISOC Jamaica programmes: Internet Governance, Community Networks, Internet of Things, Cyber Security and more.')

@section('content')
    <x-page-header title="Our Work" text="The programmes through which ISOC Jamaica works for an open, secure and accessible Internet."
        :crumbs="['Our Work' => null]" :image="asset('assets/images/photos/community-networks.jpg')" />

    <section class="support-categories section-space">
        <div class="container">
            @if ($programmes->isEmpty())
                <x-empty-state icon="fa-diagram-project" title="Programmes coming soon" />
            @else
                <div class="row gutter-y-30">
                    @foreach ($programmes as $programme)
                        <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ ($loop->index % 3) * 100 }}ms">
                            <div @class(['support-categories__item', 'support-categories__item--card-bottom' => $loop->index % 3 === 1])>
                                <div class="support-categories__card tone-{{ $loop->index % 3 }}">
                                    <span class="support-categories__card__icon"><i class="fa-solid {{ $programme->icon ?: 'fa-globe' }}"></i></span>
                                    <h2 class="support-categories__card__title">{{ $programme->title }}</h2>
                                    <p class="support-categories__card__text">{{ $programme->summary }}</p>
                                    <a href="{{ route('programmes.show', $programme) }}" class="truehelp-btn truehelp-btn--white"><span>Learn More</span></a>
                                </div>
                                <p class="support-categories__item__text">{{ $programme->status->label() }} programme</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @include('partials.join-band')
@endsection
