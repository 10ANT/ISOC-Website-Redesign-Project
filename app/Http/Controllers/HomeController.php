<?php

namespace App\Http\Controllers;

use App\Enums\PersonGroup;
use App\Models\Event;
use App\Models\Person;
use App\Models\Post;
use App\Models\Programme;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $events = Event::published()->upcoming()->with('category')->take(6)->get();

        return view('home', [
            // A featured upcoming event takes the spotlight; otherwise the soonest one.
            'nextEvent' => $events->firstWhere('is_featured', true) ?? $events->first(),
            'events' => $events,
            'programmes' => Programme::where('is_featured', true)->ordered()->take(4)->get(),
            'posts' => Post::latestPublished()->with(['category', 'author'])->take(6)->get(),
            'resources' => Resource::published()
                ->visibleTo($request->user())
                ->with('category')
                ->orderByDesc('is_featured')
                ->orderByDesc('published_on')
                ->take(3)
                ->get(),
            'foundingCount' => Person::where('group', PersonGroup::Founding)->count(),
        ]);
    }
}
