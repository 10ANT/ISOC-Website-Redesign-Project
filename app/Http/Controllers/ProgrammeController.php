<?php

namespace App\Http\Controllers;

use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgrammeController extends Controller
{
    public function index(): View
    {
        return view('programmes.index', [
            'programmes' => Programme::ordered()->get(),
        ]);
    }

    public function show(Request $request, Programme $programme): View
    {
        return view('programmes.show', [
            'programme' => $programme,
            'events' => $programme->events()->published()->upcoming()->with('category')->take(3)->get(),
            'posts' => $programme->posts()->latestPublished()->with(['category', 'author'])->take(3)->get(),
            'resources' => $programme->resources()->published()->visibleTo($request->user())->with('category')->latest('published_on')->take(4)->get(),
            'others' => Programme::whereKeyNot($programme->id)->ordered()->get(),
        ]);
    }
}
