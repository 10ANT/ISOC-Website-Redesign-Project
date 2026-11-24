<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\StoresImages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PageController as PublicPageController;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    use StoresImages;

    /** Pages the site structure depends on; they can be edited but not deleted or renamed. */
    private const PROTECTED = [...PublicPageController::ABOUT_PAGES, 'privacy-policy'];

    public function index(): View
    {
        return view('admin.pages.index', [
            'pages' => Page::orderBy('title')->get(),
            'protected' => self::PROTECTED,
        ]);
    }

    public function create(): View
    {
        return $this->form(new Page(['is_published' => true]));
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $page = new Page($request->payload());
        $page->image = $this->handleImage($request, null, 'pages');
        $page->save();

        return redirect()->route('admin.pages.index')->with('success', 'Page created.');
    }

    public function edit(Page $page): View
    {
        return $this->form($page);
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $data = $request->payload();

        if ($this->isProtected($page)) {
            unset($data['slug']);
        }

        $page->fill($data);
        $page->image = $this->handleImage($request, $page->image, 'pages');
        $page->save();

        return redirect()->route('admin.pages.index')->with('success', 'Page updated.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        if ($this->isProtected($page)) {
            return back()->with('error', 'This page is part of the site structure and cannot be deleted. You can unpublish it instead.');
        }

        $this->deleteImage($page->image);
        $page->delete();

        return back()->with('success', 'Page deleted.');
    }

    private function isProtected(Page $page): bool
    {
        return in_array($page->getOriginal('slug'), self::PROTECTED, true);
    }

    private function form(Page $page): View
    {
        return view('admin.pages.form', [
            'page' => $page,
            'isProtected' => $page->exists && $this->isProtected($page),
        ]);
    }
}
