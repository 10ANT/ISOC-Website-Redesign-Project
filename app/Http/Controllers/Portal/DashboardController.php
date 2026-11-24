<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $approved = $user->canViewMemberContent();

        return view('portal.dashboard', [
            'user' => $user,
            'approved' => $approved,
            'announcements' => $approved ? Announcement::active()->take(4)->get() : collect(),
            'memberResources' => $approved
                ? Resource::published()->with('category')->where('is_members_only', true)->latest('published_on')->take(5)->get()
                : collect(),
            'events' => Event::published()->upcoming()->with('category')->take(3)->get(),
        ]);
    }
}
