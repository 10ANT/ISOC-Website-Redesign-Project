<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\Post;
use App\Models\Resource;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Approved members', 'value' => User::where('role', UserRole::Member)->where('status', UserStatus::Approved)->count(), 'icon' => 'fa-users', 'route' => 'admin.users.index'],
                ['label' => 'Upcoming events', 'value' => Event::published()->upcoming()->count(), 'icon' => 'fa-calendar-days', 'route' => 'admin.events.index'],
                ['label' => 'Published news', 'value' => Post::published()->count(), 'icon' => 'fa-newspaper', 'route' => 'admin.posts.index'],
                ['label' => 'Resources', 'value' => Resource::published()->count(), 'icon' => 'fa-folder-open', 'route' => 'admin.resources.index'],
            ],
            'pendingMembers' => User::pending()->latest()->take(5)->get(),
            'unreadCount' => ContactMessage::unread()->count(),
            'messages' => ContactMessage::latest()->take(5)->get(),
            'events' => Event::published()->upcoming()->take(5)->get(),
            'drafts' => Post::whereNull('published_at')->latest('updated_at')->take(5)->get(),
        ]);
    }
}
