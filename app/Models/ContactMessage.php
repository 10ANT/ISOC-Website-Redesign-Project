<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    public const ENQUIRY_TYPES = [
        'general' => 'General enquiry',
        'meeting' => 'Request a meeting',
        'event_request' => 'Request an event or speaker',
        'support' => 'Support the Chapter',
        'project' => 'Suggest a project',
        'membership' => 'Membership',
        'events' => 'Events & training',
        'programmes' => 'Programmes & partnerships',
        'media' => 'Media & press',
        'volunteer' => 'Volunteering',
    ];

    protected $fillable = ['name', 'email', 'phone', 'enquiry_type', 'subject', 'message'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }

    public function enquiryLabel(): string
    {
        return self::ENQUIRY_TYPES[$this->enquiry_type] ?? ucfirst($this->enquiry_type);
    }

    public function scopeUnread(Builder $query): void
    {
        $query->whereNull('read_at');
    }
}
