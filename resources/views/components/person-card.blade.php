{{-- Template "volunteer card". Shows a neutral silhouette until a photo is uploaded. --}}
@props(['person'])

<div class="volunteer-card">
    <div class="volunteer-card__image">
        <img src="{{ $person->image_url ?? asset('assets/images/person-placeholder.svg') }}"
            alt="{{ $person->image_url ? $person->name : 'Photo of '.$person->name.' coming soon' }}" loading="lazy">
    </div>
    <div class="volunteer-card__info">
        <h3 class="volunteer-card__name">{{ $person->name }}</h3>
        <p class="volunteer-card__designation">{{ $person->position ?? $person->sector ?? 'Member' }}</p>
    </div>
</div>
