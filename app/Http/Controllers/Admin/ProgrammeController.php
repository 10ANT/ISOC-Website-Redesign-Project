<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProgrammeStatus;
use App\Http\Controllers\Admin\Concerns\StoresImages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProgrammeRequest;
use App\Models\Programme;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProgrammeController extends Controller
{
    use StoresImages;

    public function index(): View
    {
        return view('admin.programmes.index', [
            'programmes' => Programme::withCount(['events', 'posts', 'resources'])->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Programme(['status' => ProgrammeStatus::Active]));
    }

    public function store(ProgrammeRequest $request): RedirectResponse
    {
        $programme = new Programme($request->payload());
        $programme->image = $this->handleImage($request, null, 'programmes');
        $programme->save();

        return redirect()->route('admin.programmes.index')->with('success', 'Programme created.');
    }

    public function edit(Programme $programme): View
    {
        return $this->form($programme);
    }

    public function update(ProgrammeRequest $request, Programme $programme): RedirectResponse
    {
        $programme->fill($request->payload());
        $programme->image = $this->handleImage($request, $programme->image, 'programmes');
        $programme->save();

        return redirect()->route('admin.programmes.index')->with('success', 'Programme updated.');
    }

    public function destroy(Programme $programme): RedirectResponse
    {
        $this->deleteImage($programme->image);
        $programme->delete();

        return back()->with('success', 'Programme deleted. Linked events, news and resources were kept.');
    }

    private function form(Programme $programme): View
    {
        return view('admin.programmes.form', [
            'programme' => $programme,
            'statuses' => ProgrammeStatus::cases(),
        ]);
    }
}
