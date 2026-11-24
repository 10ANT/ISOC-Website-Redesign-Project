<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CategoryType;
use App\Enums\ResourceType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ResourceRequest;
use App\Models\Category;
use App\Models\Programme;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function index(Request $request): View
    {
        $resources = Resource::with('category')
            ->when($request->query('q'), fn ($q, $term) => $q->where('title', 'like', "%{$term}%"))
            ->when(ResourceType::tryFrom((string) $request->query('type')), fn ($q, $type) => $q->where('type', $type))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.resources.index', [
            'resources' => $resources,
            'types' => ResourceType::cases(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Resource(['is_published' => true, 'published_on' => now()]));
    }

    public function store(ResourceRequest $request): RedirectResponse
    {
        $resource = new Resource($request->payload());
        $this->handleFile($request, $resource);
        $resource->save();

        return redirect()->route('admin.resources.index')->with('success', 'Resource created.');
    }

    public function edit(Resource $resource): View
    {
        return $this->form($resource);
    }

    public function update(ResourceRequest $request, Resource $resource): RedirectResponse
    {
        $resource->fill($request->payload());
        $this->handleFile($request, $resource);
        $resource->save();

        return redirect()->route('admin.resources.index')->with('success', 'Resource updated.');
    }

    public function destroy(Resource $resource): RedirectResponse
    {
        $this->deleteFile($resource);
        $resource->delete();

        return back()->with('success', 'Resource deleted.');
    }

    /** Files go to the private disk so members-only downloads stay protected. */
    private function handleFile(Request $request, Resource $resource): void
    {
        if ($request->boolean('remove_file') || $request->hasFile('file')) {
            $this->deleteFile($resource);
        }

        if ($file = $request->file('file')) {
            $resource->file_path = $file->store('resources', Resource::DISK);
            $resource->file_name = $file->getClientOriginalName();
            $resource->file_size = $file->getSize();
        }
    }

    private function deleteFile(Resource $resource): void
    {
        if ($resource->file_path) {
            Storage::disk(Resource::DISK)->delete($resource->file_path);
        }

        $resource->file_path = $resource->file_name = $resource->file_size = null;
    }

    private function form(Resource $resource): View
    {
        return view('admin.resources.form', [
            'resource' => $resource,
            'types' => ResourceType::cases(),
            'categories' => Category::ofType(CategoryType::Resource)->get(),
            'programmes' => Programme::ordered()->get(['id', 'title']),
        ]);
    }
}
