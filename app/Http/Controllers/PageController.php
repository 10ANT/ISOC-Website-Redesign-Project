<?php

namespace App\Http\Controllers;

use App\Enums\PersonGroup;
use App\Models\Event;
use App\Models\Page;
use App\Models\Person;
use App\Models\Programme;
use Illuminate\View\View;

class PageController extends Controller
{
    /** Pages that make up the About section, in menu order. */
    public const ABOUT_PAGES = ['who-we-are', 'mission', 'history', 'our-plans'];

    public function about(): View
    {
        $pages = Page::published()->whereIn('slug', self::ABOUT_PAGES)->get()->keyBy('slug');

        return view('pages.about', [
            'pages' => $pages,
            'board' => Person::where('group', PersonGroup::Board)->ordered()->take(4)->get(),
            'programmes' => Programme::ordered()->get(),
        ]);
    }

    public function leadership(): View
    {
        $people = Person::ordered()->get()->groupBy(fn (Person $p) => $p->group->value);

        return view('pages.leadership', [
            'groups' => collect(PersonGroup::cases())->mapWithKeys(fn (PersonGroup $g) => [
                $g->value => ['label' => $g->label(), 'people' => $people->get($g->value, collect())],
            ]),
        ]);
    }

    public function show(Page $page): View
    {
        abort_unless($page->is_published, 404);

        return view('pages.show', [
            'page' => $page,
            'aboutPages' => Page::published()->whereIn('slug', self::ABOUT_PAGES)->get(['title', 'slug']),
        ]);
    }

    public function privacy(): View
    {
        return $this->show(Page::where('slug', 'privacy-policy')->firstOrFail());
    }

    public function membership(): View
    {
        return view('pages.membership');
    }

    public function getInvolved(): View
    {
        return view('pages.get-involved', [
            'events' => Event::published()->upcoming()->with('category')->take(3)->get(),
        ]);
    }
}
