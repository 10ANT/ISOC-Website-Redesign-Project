<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Resolves an image column to a public URL. Bundled theme images live under
 * "assets/…" in /public; uploaded images live on the "public" disk.
 */
trait HasImage
{
    protected function imageColumn(): string
    {
        return 'image';
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            $path = $this->{$this->imageColumn()};

            if (blank($path)) {
                return null;
            }

            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            return Str::startsWith($path, 'assets/') ? asset($path) : Storage::disk('public')->url($path);
        });
    }
}
