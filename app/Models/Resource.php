<?php

namespace App\Models;

use App\Enums\ResourceType;
use App\Models\Concerns\HasSlug;
use App\Support\FileSize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resource extends Model
{
    use HasSlug;

    /** Files are stored on the private "local" disk and streamed through a controller. */
    public const DISK = 'local';

    protected $fillable = [
        'category_id', 'programme_id', 'title', 'slug', 'description', 'type', 'author',
        'published_on', 'external_url', 'is_members_only', 'is_published', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'type' => ResourceType::class,
            'published_on' => 'date',
            'is_members_only' => 'boolean',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'downloads' => 'integer',
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

    public function hasFile(): bool
    {
        return filled($this->file_path);
    }

    protected function fileSizeForHumans(): Attribute
    {
        return Attribute::get(fn () => $this->file_size ? FileSize::format($this->file_size) : null);
    }

    protected function fileExtension(): Attribute
    {
        return Attribute::get(fn () => $this->file_name ? strtoupper(pathinfo($this->file_name, PATHINFO_EXTENSION)) : null);
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /** Hide members-only resources from guests and unapproved members. */
    public function scopeVisibleTo(Builder $query, ?User $user): void
    {
        if (! $user?->canViewMemberContent()) {
            $query->where('is_members_only', false);
        }
    }
}
