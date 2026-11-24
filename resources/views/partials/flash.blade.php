@php
    $flashes = array_filter([
        'success' => session('success'),
        'error' => session('error'),
        'info' => session('status'),
    ]);
    $icons = ['success' => 'fa-circle-check', 'error' => 'fa-circle-exclamation', 'info' => 'fa-circle-info'];
@endphp

@if ($flashes)
    <div class="flash-wrap">
        <div class="container">
            @foreach ($flashes as $type => $message)
                <div class="isoc-notice isoc-notice--{{ $type }}" role="{{ $type === 'error' ? 'alert' : 'status' }}">
                    <i class="fa-solid {{ $icons[$type] }}"></i>
                    <div>{{ $message }}</div>
                </div>
            @endforeach
        </div>
    </div>
@endif
