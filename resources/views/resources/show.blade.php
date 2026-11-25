@extends('layouts.app')

@section('title', $resource->title)
@section('description', Str::limit($resource->description, 160))

@php $canDownload = ! $resource->is_members_only || auth()->user()?->canViewMemberContent(); @endphp

@section('content')
    <x-page-header :title="$resource->title" :crumbs="['Resources' => route('resources.index'), Str::limit($resource->title, 40) => null]"
        :image="asset('assets/images/photos/about-datacentre.jpg')" />

    <section class="event-details section-space">
        <div class="container">
            <div class="row gutter-y-40">
                <div class="col-lg-8">
                    <div class="event-details__content">
                        <div class="meta-row">
                            <span class="meta-label"><i class="fa-solid {{ $resource->type->icon() }}"></i> {{ $resource->type->label() }}</span>
                            @if ($resource->category)
                                <a href="{{ route('resources.index', ['topic' => $resource->category->slug]) }}" class="meta-label">{{ $resource->category->name }}</a>
                            @endif
                            @if ($resource->is_members_only)
                                <span class="meta-label meta-label--muted"><i class="fa-solid fa-lock"></i> Leaders only</span>
                            @endif
                        </div>
                        <h2 class="event-details__title">{{ $resource->title }}</h2>
                        <div class="event-details__about">
                            <div class="isoc-prose">
                                @foreach (preg_split('/\R{2,}/', $resource->description) as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </div>
                            @if ($resource->programme)
                                <div class="isoc-notice mt-4">
                                    <i class="fa-solid fa-diagram-project"></i>
                                    <div>Part of our work on <a href="{{ route('programmes.show', $resource->programme) }}">{{ $resource->programme->title }}</a>.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 wow fadeInRight" data-wow-duration="1500ms">
                    <div class="event-details__organiser">
                        <h3 class="event-details__organiser__title">About this resource</h3>
                        <ul class="event-details__organiser__list list-unstyled">
                            @if ($resource->author)
                                <li><span class="event-details__organiser__list__title">Author :</span><span class="event-details__organiser__list__text">{{ $resource->author }}</span></li>
                            @endif
                            @if ($resource->published_on)
                                <li><span class="event-details__organiser__list__title">Published :</span><span class="event-details__organiser__list__text">{{ $resource->published_on->format('F j, Y') }}</span></li>
                            @endif
                            <li>
                                <span class="event-details__organiser__list__title">Format :</span>
                                <span class="event-details__organiser__list__text">
                                    @if ($resource->hasFile()){{ $resource->file_extension }}{{ $resource->file_size_for_humans ? ' · '.$resource->file_size_for_humans : '' }}@else External link @endif
                                </span>
                            </li>
                        </ul>

                        @if ($canDownload)
                            <a href="{{ route('resources.download', $resource) }}" class="truehelp-btn" @unless ($resource->hasFile()) target="_blank" rel="noopener" @endunless>
                                <span>{{ $resource->hasFile() ? 'Download' : 'Open Resource' }}</span>
                            </a>
                        @else
                            <div class="isoc-notice isoc-notice--warning mt-4 mb-0">
                                <i class="fa-solid fa-lock"></i>
                                <div>This resource is for chapter leaders. <a href="{{ route('contact') }}">Contact us</a> if you need a copy.</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($related->isNotEmpty())
                <div class="mt-5 pt-4">
                    <x-sec-title tagline="More on this topic" title="Related resources" />
                    <div class="row gutter-y-30">
                        @foreach ($related as $item)
                            <div class="col-xl-4 col-md-6"><x-resource-card :resource="$item" :index="$loop->index" /></div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
