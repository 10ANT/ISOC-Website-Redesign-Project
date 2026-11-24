<?php

namespace App\Models;

use App\Enums\ProgrammeStatus;
use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programme extends Model
{
    use HasImage, HasSlug;

    protected $fillable = [
        'title', 'slug', 'icon', 'summary', 'body', 'image', 'status', 'is_featured', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'status' => ProgrammeStatus::class,
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('title');
    }
}
