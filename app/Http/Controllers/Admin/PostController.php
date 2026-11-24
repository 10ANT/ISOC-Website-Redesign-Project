<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CategoryType;
use App\Http\Controllers\Admin\Concerns\StoresImages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Programme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    use StoresImages;

    public function index(Request $request): View
    {
        $posts = Post::with(['category', 'author'])
            ->when($request->query('q'), fn ($q, $term) => $q->where('title', 'like', "%{$term}%"))
            ->when($request->query('status') === 'draft', fn ($q) => $q->whereNull('published_at'))
            ->when($request->query('status') === 'published', fn ($q) => $q->published())
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return $this->form(new Post);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $post = new Post($request->payload());
        $post->user_id = $request->user()->id;
        $post->image = $this->handleImage($request, null, 'news');
        $post->save();

        return redirect()->route('admin.posts.index')->with('success', 'News post created.');
    }

    public function edit(Post $post): View
    {
        return $this->form($post);
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $post->fill($request->payload());
        $post->image = $this->handleImage($request, $post->image, 'news');
        $post->save();

        return redirect()->route('admin.posts.index')->with('success', 'News post updated.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->deleteImage($post->image);
        $post->delete();

        return back()->with('success', 'News post deleted.');
    }

    private function form(Post $post): View
    {
        return view('admin.posts.form', [
            'post' => $post,
            'categories' => Category::ofType(CategoryType::News)->get(),
            'programmes' => Programme::ordered()->get(['id', 'title']),
        ]);
    }
}
