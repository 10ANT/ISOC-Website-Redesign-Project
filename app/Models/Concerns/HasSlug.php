<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Generates a unique slug from the model's title when one isn't supplied.
 */
trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            if (blank($model->slug)) {
                $model->slug = $model->uniqueSlug(Str::slug($model->{$model->slugSource()}));
            }
        });
    }

    protected function slugSource(): string
    {
        return 'title';
    }

    protected function uniqueSlug(string $base): string
    {
        $base = $base ?: Str::lower(Str::random(8));
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($this->exists, fn ($q) => $q->whereKeyNot($this->getKey()))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
