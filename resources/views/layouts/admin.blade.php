<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') · ISOC Jamaica Admin</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400..800&family=Geologica:wght@500..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    @stack('styles')
</head>
<body>
@php
    $nav = [
        'Overview' => [
            ['admin.dashboard', 'Dashboard', 'fa-gauge-high', 'admin.dashboard'],
        ],
        'Content' => [
            ['admin.posts.index', 'News', 'fa-newspaper', 'admin.posts.*'],
            ['admin.events.index', 'Events', 'fa-calendar-days', 'admin.events.*'],
            ['admin.resources.index', 'Resources', 'fa-folder-open', 'admin.resources.*'],
            ['admin.programmes.index', 'Our Work', 'fa-diagram-project', 'admin.programmes.*'],
            ['admin.pages.index', 'Pages', 'fa-file-lines', 'admin.pages.*'],
            ['admin.people.index', 'Leadership & People', 'fa-user-tie', 'admin.people.*'],
            ['admin.categories.index', 'Categories', 'fa-tags', 'admin.categories.*'],
            ['admin.media.index', 'Media Library', 'fa-images', 'admin.media.*'],
        ],
        'People' => [
            ['admin.messages.index', 'Messages & Requests', 'fa-inbox', 'admin.messages.*'],
        ],
    ];

    if (auth()->user()->can('manage-users')) {
        $nav['People'][] = ['admin.users.index', 'Website Users', 'fa-users', 'admin.users.*'];
    }

    $nav['Settings'] = [['admin.settings.edit', 'Site Settings', 'fa-sliders', 'admin.settings.*']];
@endphp

<div class="admin-shell">
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-sidebar__brand">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/images/isoc-jamaica-logo-light.png') }}" alt="ISOC Jamaica" height="56">
            </a>
        </div>
        <nav class="admin-sidebar__nav" aria-label="Admin">
            @foreach ($nav as $section => $items)
                <p class="admin-sidebar__section">{{ $section }}</p>
                @foreach ($items as [$route, $label, $icon, $pattern])
                    <a href="{{ route($route) }}" @class(['active' => request()->routeIs($pattern)])>
                        <i class="fa-solid {{ $icon }} fa-fw"></i> {{ $label }}
                    </a>
                @endforeach
            @endforeach
        </nav>
        <div class="admin-sidebar__footer">
            <a href="{{ route('home') }}" target="_blank" rel="noopener"><i class="fa-solid fa-arrow-up-right-from-square fa-fw"></i> View website</a>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <button class="btn btn-light d-lg-none" type="button" onclick="document.getElementById('adminSidebar').classList.toggle('open')" aria-label="Toggle menu">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h1 class="admin-topbar__title">@yield('title', 'Dashboard')</h1>
            <div class="dropdown ms-auto">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="admin-avatar">{{ auth()->user()->initials() }}</span>
                    <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text small text-muted">{{ auth()->user()->role->label() }}</span></li>
                    <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}"><i class="fa-solid fa-user fa-fw me-1"></i> My profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item"><i class="fa-solid fa-right-from-bracket fa-fw me-1"></i> Sign out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <main class="admin-content">
            @foreach (['success' => 'success', 'error' => 'danger'] as $key => $variant)
                @if (session($key))
                    <div class="alert alert-{{ $variant }} alert-dismissible fade show" role="alert">
                        {{ session($key) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @endforeach

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <strong>Please fix the highlighted fields.</strong>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="{{ asset('assets/vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.js"></script>
<script>
    // Markdown editor for long-form fields.
    document.querySelectorAll('textarea.js-markdown').forEach(function (el) {
        new EasyMDE({
            element: el,
            spellChecker: false,
            status: false,
            minHeight: '260px',
            forceSync: true,
            toolbar: ['heading-2', 'heading-3', 'bold', 'italic', '|', 'unordered-list', 'ordered-list', 'quote', 'link', '|', 'preview', 'guide'],
        });
    });

    // Confirm destructive actions.
    document.addEventListener('submit', function (e) {
        var msg = e.target.getAttribute('data-confirm');
        if (msg && !window.confirm(msg)) { e.preventDefault(); }
    });

    // Copy-to-clipboard buttons.
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-copy]');
        if (!btn) return;
        navigator.clipboard.writeText(btn.getAttribute('data-copy')).then(function () {
            var original = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Copied';
            setTimeout(function () { btn.innerHTML = original; }, 1500);
        });
    });
</script>
@stack('scripts')
</body>
</html>
