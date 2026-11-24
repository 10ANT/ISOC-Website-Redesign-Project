<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * A simple library of uploaded files (photos, flyers, logos) that staff
 * can copy links from when writing content.
 */
class MediaController extends Controller
{
    public function index(): View
    {
        return view('admin.media.index', [
            'items' => Media::with('uploader')->latest()->paginate(24),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'files' => ['required', 'array', 'max:10'],
            'files.*' => ['file', 'mimes:jpg,jpeg,png,webp,gif,pdf,docx,pptx,xlsx', 'max:10240'],
        ]);

        foreach ($request->file('files') as $file) {
            Media::create([
                'user_id' => $request->user()->id,
                'path' => $file->store('library', 'public'),
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        return back()->with('success', 'Files uploaded.');
    }

    public function destroy(Media $media): RedirectResponse
    {
        Storage::disk('public')->delete($media->path);
        $media->delete();

        return back()->with('success', 'File deleted.');
    }
}
