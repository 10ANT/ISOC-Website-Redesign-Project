<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait StoresImages
{
    /**
     * Handle an optional image upload (or removal) and return the new stored path.
     */
    protected function handleImage(Request $request, ?string $current, string $directory, string $field = 'image'): ?string
    {
        if ($request->boolean("remove_{$field}")) {
            $this->deleteImage($current);
            $current = null;
        }

        if ($request->hasFile($field)) {
            $this->deleteImage($current);

            return $request->file($field)->store($directory, 'public');
        }

        return $current;
    }

    /** Bundled theme images (assets/…) are never deleted. */
    protected function deleteImage(?string $path): void
    {
        if ($path && ! Str::startsWith($path, ['assets/', 'http'])) {
            Storage::disk('public')->delete($path);
        }
    }
}
