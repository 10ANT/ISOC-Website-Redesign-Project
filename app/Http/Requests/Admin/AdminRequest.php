<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/**
 * Base request for admin forms. Access is already enforced by the
 * "can:access-admin" route middleware.
 */
abstract class AdminRequest extends FormRequest
{
    public const IMAGE_RULES = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];

    public function authorize(): bool
    {
        return true;
    }

    /** Validated data ready to be filled onto the model (uploads excluded). */
    public function payload(): array
    {
        $data = collect($this->validated())->except(['image', 'photo', 'file', 'remove_image', 'remove_photo', 'remove_file'])->all();

        if (array_key_exists('slug', $data)) {
            $data['slug'] = Str::slug((string) $data['slug']) ?: null;
        }

        return $data;
    }

    /** Resolve a draft/published select plus optional date into a timestamp. */
    protected function publishedAt(): ?string
    {
        if ($this->input('status') !== 'published') {
            return null;
        }

        return $this->filled('published_at') ? $this->input('published_at') : now()->toDateTimeString();
    }
}
