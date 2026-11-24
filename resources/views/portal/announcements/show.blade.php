@extends('layouts.app')

@section('title', $announcement->title)

@section('content')
    <x-page-header :title="$announcement->title"
        :crumbs="['Member portal' => route('portal.dashboard'), 'Announcements' => route('portal.announcements.index'), Str::limit($announcement->title, 40) => null]" />

    <section class="section-space">
        <div class="container">
            @include('portal._nav')

            <div class="row">
                <article class="col-lg-9">
                    <p class="meta-row">
                        <span class="meta-label meta-label--muted"><i class="fa-regular fa-calendar"></i>{{ $announcement->published_at->format('F j, Y') }}</span>
                        @if ($announcement->is_pinned)<span class="meta-label"><i class="fa-solid fa-thumbtack"></i> Pinned</span>@endif
                    </p>
                    <div class="isoc-prose">@markdown($announcement->body)</div>
                    <a href="{{ route('portal.announcements.index') }}" class="truehelp-btn mt-4"><span>All Announcements</span></a>
                </article>
            </div>
        </div>
    </section>
@endsection
