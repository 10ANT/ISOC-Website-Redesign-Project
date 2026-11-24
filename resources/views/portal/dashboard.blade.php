@extends('layouts.app')

@section('title', 'Member portal')

@section('content')
    <x-page-header title="Member Portal" :crumbs="['Member portal' => null]" />

    <section class="section-space">
        <div class="container">
            @include('portal._nav')

            <div class="portal-hero">
                <div class="d-flex align-items-center gap-3">
                    <span class="portal-hero__avatar" aria-hidden="true">{{ $user->initials() }}</span>
                    <div>
                        <h2>Welcome, {{ Str::before($user->name, ' ') ?: $user->name }}</h2>
                        <span>{{ $user->role->label() }}@if ($user->approved_at) · Member since {{ $user->approved_at->format('F Y') }}@endif</span>
                    </div>
                </div>
                <a href="{{ route('portal.profile.edit') }}" class="truehelp-btn truehelp-btn--primary"><span>Edit Profile</span></a>
            </div>

            @unless ($approved)
                <div class="isoc-notice isoc-notice--warning">
                    <i class="fa-solid fa-hourglass-half"></i>
                    <div>
                        <strong>Your membership is awaiting approval.</strong>
                        An administrator will review your application shortly. Members-only announcements and resources will appear here once you're approved.
                        In the meantime, please <a href="{{ route('portal.profile.edit') }}">complete your profile</a>.
                    </div>
                </div>
            @endunless

            <div class="row gutter-y-40">
                <div class="col-lg-7">
                    @if ($approved)
                        <div class="sidebar__single">
                            <h4 class="sidebar__title d-flex justify-content-between align-items-center">
                                Announcements
                                <a href="{{ route('portal.announcements.index') }}" class="fs-6">View all</a>
                            </h4>
                            @forelse ($announcements as $announcement)
                                <div class="announcement">
                                    <h3 class="announcement__title">
                                        @if ($announcement->is_pinned)<i class="fa-solid fa-thumbtack me-1" title="Pinned"></i>@endif
                                        <a href="{{ route('portal.announcements.show', $announcement) }}">{{ $announcement->title }}</a>
                                    </h3>
                                    <span class="announcement__date">{{ $announcement->published_at->format('F j, Y') }}</span>
                                    <p class="mb-0 mt-2">{{ Str::limit(strip_tags(\App\Providers\AppServiceProvider::markdown($announcement->body)), 160) }}</p>
                                </div>
                            @empty
                                <p class="mb-0">No announcements right now.</p>
                            @endforelse
                        </div>

                        <h3 class="mt-5 mb-4">Members-only resources</h3>
                        @forelse ($memberResources as $resource)
                            <div class="mb-4"><x-resource-card :resource="$resource" :index="$loop->index" /></div>
                        @empty
                            <p>No members-only resources have been shared yet.</p>
                        @endforelse
                    @else
                        <div class="sidebar__single">
                            <h4 class="sidebar__title">While you wait</h4>
                            <div class="isoc-prose">
                                <ul>
                                    <li>Browse our <a href="{{ route('resources.index') }}">resource library</a></li>
                                    <li>Read the <a href="{{ route('news.index') }}">latest Chapter news</a></li>
                                    <li>Learn about <a href="{{ route('programmes.index') }}">our work</a></li>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-5">
                    <div class="event-details__organiser mb-4">
                        <h3 class="event-details__organiser__title">Your details</h3>
                        <ul class="event-details__organiser__list list-unstyled">
                            <li><span class="event-details__organiser__list__title">Email :</span><span class="event-details__organiser__list__text">{{ $user->email }}</span></li>
                            <li><span class="event-details__organiser__list__title">Group :</span><span class="event-details__organiser__list__text">{{ $user->stakeholder_group ?? 'Not set' }}</span></li>
                            <li><span class="event-details__organiser__list__title">Parish :</span><span class="event-details__organiser__list__text">{{ $user->parish ?? 'Not set' }}</span></li>
                            <li><span class="event-details__organiser__list__title">Status :</span><span class="event-details__organiser__list__text">{{ $user->status->label() }}</span></li>
                        </ul>
                    </div>

                    <h3 class="mb-4">Upcoming events</h3>
                    @forelse ($events as $event)
                        <div class="mb-4"><x-event-card :event="$event" :index="$loop->index" /></div>
                    @empty
                        <p>No upcoming events right now.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
