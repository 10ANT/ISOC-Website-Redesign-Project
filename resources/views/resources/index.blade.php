@extends('layouts.app')

@section('title', 'Resource library')
@section('description', 'Reports, policy briefs, guides and presentations from ISOC Jamaica and the Internet Society.')

@section('content')
    <x-page-header title="Resource Library" text="Reports, policy briefs, guides and presentations, organised by topic and type."
        :crumbs="['Resources' => null]" :image="asset('assets/images/photos/about-datacentre.jpg')" />

    <section class="section-space">
        <div class="container">
            <form method="GET" action="{{ route('resources.index') }}" class="isoc-filter" role="search">
                <label for="res-search" class="visually-hidden">Search resources</label>
                <input type="search" id="res-search" name="q" value="{{ $search }}" placeholder="Search by title, topic or author">
                <label for="res-topic" class="visually-hidden">Topic</label>
                <select name="topic" id="res-topic">
                    <option value="">All topics</option>
                    @foreach ($topics as $topic)
                        <option value="{{ $topic->slug }}" @selected($activeTopic === $topic->slug)>{{ $topic->name }}</option>
                    @endforeach
                </select>
                <label for="res-type" class="visually-hidden">Type</label>
                <select name="type" id="res-type">
                    <option value="">All types</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}" @selected($activeType === $type)>{{ $type->label() }}</option>
                    @endforeach
                </select>
                <button type="submit" class="truehelp-btn"><span>Filter</span></button>
            </form>

            @guest
                <div class="isoc-notice isoc-notice--warning">
                    <i class="fa-solid fa-lock"></i>
                    <div>Resources marked <strong>Members</strong> can be downloaded by approved members. <a href="{{ route('login') }}">Sign in</a> or <a href="{{ route('membership') }}">become a member</a>.</div>
                </div>
            @endguest

            @if ($resources->isEmpty())
                <x-empty-state icon="fa-folder-open" title="No resources found" text="Try a different search, topic or type." />
            @else
                <div class="row gutter-y-30">
                    @foreach ($resources as $resource)
                        <div class="col-xl-4 col-md-6 wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ ($loop->index % 3) * 100 }}ms">
                            <x-resource-card :resource="$resource" :index="$loop->index" />
                        </div>
                    @endforeach
                </div>
                {{ $resources->links('vendor.pagination.isoc') }}
            @endif
        </div>
    </section>
@endsection
