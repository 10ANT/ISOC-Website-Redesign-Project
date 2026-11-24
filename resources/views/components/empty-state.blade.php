@props(['icon' => 'fa-inbox', 'title', 'text' => null])

<div class="empty-state">
    <i class="fa-solid {{ $icon }}" aria-hidden="true"></i>
    <h3>{{ $title }}</h3>
    @if ($text)<p class="mb-0">{{ $text }}</p>@endif
    {{ $slot }}
</div>
