<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\Post;
use App\Models\Resource;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Unread messages', 'value' => ContactMessage::unread()->count(), 'icon' => 'fa-inbox', 'route' => 'admin.messages.index'],
                ['label' => 'Upcoming events', 'value' => Event::published()->upcoming()->count(), 'icon' => 'fa-calendar-days', 'route' => 'admin.events.index'],
                ['label' => 'Published news', 'value' => Post::published()->count(), 'icon' => 'fa-newspaper', 'route' => 'admin.posts.index'],
                ['label' => 'Resources', 'value' => Resource::published()->count(), 'icon' => 'fa-folder-open', 'route' => 'admin.resources.index'],
            ],
            'unreadCount' => ContactMessage::unread()->count(),
            'messages' => ContactMessage::latest()->take(5)->get(),
            'events' => Event::published()->upcoming()->take(5)->get(),
            'drafts' => Post::whereNull('published_at')->latest('updated_at')->take(5)->get(),
        ]);
    }
}
