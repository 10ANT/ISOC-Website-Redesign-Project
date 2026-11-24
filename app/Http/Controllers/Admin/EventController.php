<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CategoryType;
use App\Http\Controllers\Admin\Concerns\StoresImages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EventRequest;
use App\Models\Category;
use App\Models\Event;
use App\Models\Programme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    use StoresImages;

    public function index(Request $request): View
    {
        $events = Event::with('category')
            ->when($request->query('q'), fn ($q, $term) => $q->where('title', 'like', "%{$term}%"))
            ->when($request->query('when') === 'past', fn ($q) => $q->past(), fn ($q) => $q->upcoming())
            ->paginate(15)
            ->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        return $this->form(new Event(['is_published' => true]));
    }

    public function store(EventRequest $request): RedirectResponse
    {
        $event = new Event($request->payload());
        $event->image = $this->handleImage($request, null, 'events');
        $event->save();

        return redirect()->route('admin.events.index')->with('success', 'Event created.');
    }

    public function edit(Event $event): View
    {
        return $this->form($event);
    }

    public function update(EventRequest $request, Event $event): RedirectResponse
    {
        $event->fill($request->payload());
        $event->image = $this->handleImage($request, $event->image, 'events');
        $event->save();

        return redirect()->route('admin.events.index')->with('success', 'Event updated.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->deleteImage($event->image);
        $event->delete();

        return back()->with('success', 'Event deleted.');
    }

    private function form(Event $event): View
    {
        return view('admin.events.form', [
            'event' => $event,
            'categories' => Category::ofType(CategoryType::Event)->get(),
            'programmes' => Programme::ordered()->get(['id', 'title']),
        ]);
    }
}
