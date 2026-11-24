<?php

namespace App\Http\Controllers;

use App\Enums\CategoryType;
use App\Enums\ResourceType;
use App\Models\Category;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResourceController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));
        $topic = $request->query('topic');
        $type = ResourceType::tryFrom((string) $request->query('type'));

        $resources = Resource::published()
            ->with('category')
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('author', 'like', "%{$search}%")))
            ->when($topic, fn ($q) => $q->whereHas('category', fn ($q) => $q->where('slug', $topic)))
            ->when($type, fn ($q) => $q->where('type', $type))
            ->orderByDesc('published_on')
            ->paginate(9)
            ->withQueryString();

        return view('resources.index', [
            'resources' => $resources,
            'search' => $search,
            'activeTopic' => $topic,
            'activeType' => $type,
            'topics' => Category::ofType(CategoryType::Resource)->get(),
            'types' => ResourceType::cases(),
        ]);
    }

    public function show(Resource $resource): View
    {
        abort_unless($resource->is_published, 404);

        $resource->load(['category', 'programme']);

        return view('resources.show', [
            'resource' => $resource,
            'related' => Resource::published()
                ->with('category')
                ->visibleTo(request()->user())
                ->whereKeyNot($resource->id)
                ->where('category_id', $resource->category_id)
                ->take(3)
                ->get(),
        ]);
    }

    public function download(Request $request, Resource $resource): StreamedResponse|RedirectResponse
    {
        abort_unless($resource->is_published, 404);

        if ($resource->is_members_only && ! $request->user()?->canViewMemberContent()) {
            return redirect()->guest(route('login'))
                ->with('status', 'This resource is available to approved ISOC Jamaica members. Please sign in.');
        }

        $resource->increment('downloads');

        if (! $resource->hasFile()) {
            abort_if(blank($resource->external_url), 404);

            return redirect()->away($resource->external_url);
        }

        abort_unless(Storage::disk(Resource::DISK)->exists($resource->file_path), 404);

        return Storage::disk(Resource::DISK)->download($resource->file_path, $resource->file_name);
    }
}
