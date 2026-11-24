<?php

namespace App\Http\Controllers;

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $past = $request->query('when') === 'past';
        $search = trim((string) $request->query('q'));
        $categorySlug = $request->query('category');

        $events = Event::published()
            ->with('category')
            ->when($past, fn ($q) => $q->past(), fn ($q) => $q->upcoming())
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$search}%")
                ->orWhere('summary', 'like', "%{$search}%")))
            ->when($categorySlug, fn ($q) => $q->whereHas('category', fn ($q) => $q->where('slug', $categorySlug)))
            ->paginate(9)
            ->withQueryString();

        return view('events.index', [
            'events' => $events,
            'past' => $past,
            'search' => $search,
            'categories' => Category::ofType(CategoryType::Event)->get(),
            'activeCategory' => $categorySlug,
        ]);
    }

    public function show(Event $event): View
    {
        abort_unless($event->is_published, 404);

        $event->load(['category', 'programme']);

        return view('events.show', [
            'event' => $event,
            'related' => Event::published()->upcoming()->with('category')->whereKeyNot($event->id)->take(3)->get(),
            'resources' => $event->programme
                ? $event->programme->resources()->published()->visibleTo(request()->user())->with('category')->take(3)->get()
                : collect(),
        ]);
    }
}
