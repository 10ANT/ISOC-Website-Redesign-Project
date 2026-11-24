<?php

namespace App\Models;

use App\Enums\PersonGroup;
use App\Models\Concerns\HasImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasImage;

    protected $fillable = ['name', 'position', 'group', 'sector', 'bio', 'photo', 'sort_order'];

    protected function casts(): array
    {
        return [
            'group' => PersonGroup::class,
            'sort_order' => 'integer',
        ];
    }

    protected function imageColumn(): string
    {
        return 'photo';
    }

    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('name');
    }
}
