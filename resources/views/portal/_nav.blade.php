<nav class="portal-nav" aria-label="Member portal">
    <a href="{{ route('portal.dashboard') }}" @class(['active' => request()->routeIs('portal.dashboard')])><i class="fa-solid fa-house"></i>Dashboard</a>
    @if (auth()->user()->canViewMemberContent())
        <a href="{{ route('portal.announcements.index') }}" @class(['active' => request()->routeIs('portal.announcements.*')])><i class="fa-solid fa-bullhorn"></i>Announcements</a>
        <a href="{{ route('resources.index') }}"><i class="fa-solid fa-folder-open"></i>Resources</a>
    @endif
    <a href="{{ route('portal.profile.edit') }}" @class(['active' => request()->routeIs('portal.profile.*')])><i class="fa-solid fa-user-pen"></i>My profile</a>
    @can('access-admin')
        <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high"></i>Admin dashboard</a>
    @endcan
</nav>
