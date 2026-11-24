<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Page;
use App\Models\Post;
use App\Models\Programme;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Site-wide search across pages, programmes, events, news and resources.
 */
class SearchController extends Controller
{
    private const PER_GROUP = 8;

    public function __invoke(Request $request): View
    {
        $term = trim((string) $request->query('q'));
        $results = collect();

        if (mb_strlen($term) >= 2) {
            $like = '%'.$term.'%';
            $match = fn (array $columns) => fn ($q) => collect($columns)
                ->each(fn ($column, $i) => $i === 0 ? $q->where($column, 'like', $like) : $q->orWhere($column, 'like', $like));

            $results = collect([
                'Pages' => Page::published()->where($match(['title', 'summary', 'body']))->take(self::PER_GROUP)->get()
                    ->map(fn ($p) => $this->item($p->title, $p->summary, route('pages.show', $p))),
                'Our Work' => Programme::where($match(['title', 'summary', 'body']))->take(self::PER_GROUP)->get()
                    ->map(fn ($p) => $this->item($p->title, $p->summary, route('programmes.show', $p))),
                'Events' => Event::published()->where($match(['title', 'summary', 'description']))->latest('starts_at')->take(self::PER_GROUP)->get()
                    ->map(fn ($e) => $this->item($e->title, $e->summary, route('events.show', $e), $e->starts_at->format('M j, Y'))),
                'News' => Post::latestPublished()->where($match(['title', 'excerpt', 'body']))->take(self::PER_GROUP)->get()
                    ->map(fn ($p) => $this->item($p->title, $p->excerpt, route('news.show', $p), $p->published_at->format('M j, Y'))),
                'Resources' => Resource::published()->visibleTo($request->user())->where($match(['title', 'description', 'author']))->take(self::PER_GROUP)->get()
                    ->map(fn ($r) => $this->item($r->title, $r->description, route('resources.show', $r), $r->type->label())),
            ])->filter->isNotEmpty();
        }

        return view('search', [
            'term' => $term,
            'results' => $results,
            'total' => $results->flatten(1)->count(),
        ]);
    }

    private function item(string $title, ?string $text, string $url, ?string $meta = null): array
    {
        return compact('title', 'text', 'url', 'meta');
    }
}
