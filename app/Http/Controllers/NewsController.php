<?php

namespace App\Http\Controllers;

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $categorySlug = $request->query('category');
        $tag = $request->query('tag');
        $year = $request->integer('year') ?: null;

        $posts = Post::latestPublished()
            ->with(['category', 'author'])
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")))
            ->when($categorySlug, fn ($q) => $q->whereHas('category', fn ($q) => $q->where('slug', $categorySlug)))
            ->when($tag, fn ($q) => $q->whereJsonContains('tags', $tag))
            ->when($year, fn ($q) => $q->whereYear('published_at', $year))
            ->paginate(6)
            ->withQueryString();

        return view('news.index', [
            'posts' => $posts,
            'search' => $search,
            'activeCategory' => $categorySlug,
            'activeTag' => $tag,
            ...$this->sidebar(),
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless($post->is_published || request()->user()?->isStaff(), 404);

        $post->load(['category', 'author', 'programme']);

        return view('news.show', [
            'post' => $post,
            'related' => Post::latestPublished()
                ->with(['category', 'author'])
                ->whereKeyNot($post->id)
                ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
                ->take(2)
                ->get(),
            ...$this->sidebar(),
        ]);
    }

    /** Shared sidebar data: categories with counts, recent posts, years. */
    private function sidebar(): array
    {
        return [
            'categories' => Category::ofType(CategoryType::News)
                ->withCount(['posts' => fn ($q) => $q->published()])
                ->get(),
            'recent' => Post::latestPublished()->take(3)->get(['id', 'title', 'slug', 'image', 'published_at']),
            'years' => Post::published()->pluck('published_at')->map->year->unique()->sortDesc()->values(),
        ];
    }
}
