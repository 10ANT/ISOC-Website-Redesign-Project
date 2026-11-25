@props(['tagline', 'title', 'center' => false])

<div {{ $attributes->class(['sec-title', 'sec-title--center' => $center]) }}>
    <div class="sec-title__top bw-split-in-right">
        <img src="{{ asset('assets/images/sec-title-mark.png') }}" alt="" class="sec-title__shape" width="18" height="18">
        <p class="sec-title__tagline">{{ $tagline }}</p>
    </div>
    <h2 class="sec-title__title bw-split-in-left">{{ $title }}</h2>
</div>
