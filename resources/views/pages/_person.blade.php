<article class="person-card">
    <div class="person-card__photo" aria-hidden="true">
        @if ($person->image_url)
            <img src="{{ $person->image_url }}" alt="" loading="lazy">
        @else
            {{ $person->initials() }}
        @endif
    </div>
    <h3 class="person-card__name">{{ $person->name }}</h3>
    @if ($person->position)<p class="person-card__role">{{ $person->position }}</p>@endif
    @if ($person->bio)<p class="person-card__bio">{{ Str::limit($person->bio, 160) }}</p>@endif
</article>
