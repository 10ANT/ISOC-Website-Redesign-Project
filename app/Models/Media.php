<?php

namespace App\Models;

use App\Support\FileSize;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = ['user_id', 'path', 'original_name', 'mime_type', 'size', 'alt_text'];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn () => Storage::disk('public')->url($this->path));
    }

    protected function isImage(): Attribute
    {
        return Attribute::get(fn () => Str::startsWith((string) $this->mime_type, 'image/'));
    }

    protected function sizeForHumans(): Attribute
    {
        return Attribute::get(fn () => FileSize::format($this->size));
    }
}
