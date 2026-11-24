<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasImage, HasSlug;

    protected $fillable = [
        'category_id', 'programme_id', 'user_id', 'title', 'slug', 'excerpt',
        'body', 'image', 'tags', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function isPublished(): Attribute
    {
        return Attribute::get(fn () => $this->published_at !== null && $this->published_at->lte(now()));
    }

    /** Rough reading time at ~200 words per minute. */
    protected function readingTime(): Attribute
    {
        return Attribute::get(fn () => max(1, (int) ceil(str_word_count(strip_tags($this->body)) / 200)));
    }

    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeLatestPublished(Builder $query): void
    {
        $query->published()->orderByDesc('published_at');
    }
}
