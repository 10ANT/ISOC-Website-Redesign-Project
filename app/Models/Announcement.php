<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = ['user_id', 'title', 'body', 'is_pinned', 'published_at', 'expires_at'];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Published, not expired, pinned first then newest. */
    public function scopeActive(Builder $query): void
    {
        $now = now();

        $query->whereNotNull('published_at')
            ->where('published_at', '<=', $now)
            ->where(fn (Builder $q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', $now))
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at');
    }
}
