<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasImage, HasSlug;

    protected $fillable = [
        'category_id', 'programme_id', 'title', 'slug', 'summary', 'description',
        'starts_at', 'ends_at', 'venue', 'online_url', 'registration_url',
        'image', 'is_published', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
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

    /** The moment the event is considered over (end time, or end of its start day). */
    protected function endsAtOrDefault(): Attribute
    {
        return Attribute::get(fn () => $this->ends_at ?? $this->starts_at->copy()->endOfDay());
    }

    /** Status is derived from dates so it never needs manual updating. */
    protected function status(): Attribute
    {
        return Attribute::get(function () {
            $now = now();

            return match (true) {
                $this->starts_at->isFuture() => EventStatus::Upcoming,
                $this->ends_at_or_default->gte($now) => EventStatus::Live,
                default => EventStatus::Past,
            };
        });
    }

    protected function isOnline(): Attribute
    {
        return Attribute::get(fn () => filled($this->online_url) && blank($this->venue));
    }

    protected function locationLabel(): Attribute
    {
        return Attribute::get(fn () => match (true) {
            filled($this->venue) && filled($this->online_url) => $this->venue.' & online',
            filled($this->venue) => $this->venue,
            filled($this->online_url) => 'Online',
            default => 'To be announced',
        });
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /** Upcoming or currently running events, soonest first. */
    public function scopeUpcoming(Builder $query): void
    {
        $now = now();

        $query->where(function (Builder $q) use ($now) {
            $q->where('starts_at', '>=', $now)
                ->orWhere('ends_at', '>=', $now)
                ->orWhere(fn (Builder $q) => $q->whereNull('ends_at')->where('starts_at', '>=', $now->copy()->startOfDay()));
        })->orderBy('starts_at');
    }

    public function scopePast(Builder $query): void
    {
        $now = now();

        $query->where(function (Builder $q) use ($now) {
            $q->where('ends_at', '<', $now)
                ->orWhere(fn (Builder $q) => $q->whereNull('ends_at')->where('starts_at', '<', $now->copy()->startOfDay()));
        })->orderByDesc('starts_at');
    }
}
