{{-- $iconSet: 'truehelp' uses the template icon font, otherwise Font Awesome brands. --}}
@php
    $truehelp = ($iconSet ?? null) === 'truehelp';
    $networks = [
        'social_facebook' => ['icon-facebook', 'fa-facebook-f', 'Facebook'],
        'social_x' => ['icon-x-2', 'fa-x-twitter', 'X'],
        'social_linkedin' => ['icon-linkedin', 'fa-linkedin-in', 'LinkedIn'],
        'social_instagram' => ['icon-instagram', 'fa-instagram', 'Instagram'],
        'social_youtube' => ['icon-youtube', 'fa-youtube', 'YouTube'],
    ];
@endphp

@foreach ($networks as $key => [$templateIcon, $faIcon, $label])
    @if (! empty($settings[$key]))
        <a href="{{ $settings[$key] }}" target="_blank" rel="noopener">
            <i class="{{ $truehelp ? $templateIcon : 'fab '.$faIcon }}" aria-hidden="true"></i>
            <span class="sr-only">ISOC Jamaica on {{ $label }}</span>
        </a>
    @endif
@endforeach
