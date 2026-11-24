<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CategoryType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount(['posts', 'events', 'resources'])->orderBy('name')->get()
            ->groupBy(fn (Category $c) => $c->type->value);

        return view('admin.categories.index', [
            'groups' => collect(CategoryType::cases())->map(fn (CategoryType $type) => [
                'type' => $type,
                'categories' => $categories->get($type->value, collect()),
            ]),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return back()->with('success', 'Category added.');
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return back()->with('success', 'Category renamed.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return back()->with('success', 'Category deleted. Items in it are now uncategorised.');
    }
}
