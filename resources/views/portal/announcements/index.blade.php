@extends('layouts.app')

@section('title', 'Member announcements')

@section('content')
    <x-page-header title="Announcements" :crumbs="['Member portal' => route('portal.dashboard'), 'Announcements' => null]" />

    <section class="section-space">
        <div class="container">
            @include('portal._nav')

            <div class="row">
                <div class="col-lg-9">
                    @forelse ($announcements as $announcement)
                        <article class="announcement">
                            <h2 class="announcement__title">
                                @if ($announcement->is_pinned)<i class="fa-solid fa-thumbtack  me-1" title="Pinned"></i>@endif
                                <a href="{{ route('portal.announcements.show', $announcement) }}">{{ $announcement->title }}</a>
                            </h2>
                            <span class="announcement__date">{{ $announcement->published_at->format('F j, Y') }}</span>
                            <p class="mb-0 mt-2">{{ Str::limit(strip_tags(\App\Providers\AppServiceProvider::markdown($announcement->body)), 220) }}</p>
                        </article>
                    @empty
                        <x-empty-state icon="fa-bullhorn" title="No announcements yet" />
                    @endforelse

                    {{ $announcements->links('vendor.pagination.isoc') }}
                </div>
            </div>
        </div>
    </section>
@endsection
