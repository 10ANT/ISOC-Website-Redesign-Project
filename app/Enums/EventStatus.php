<?php

namespace App\Enums;

enum EventStatus: string
{
    case Upcoming = 'upcoming';
    case Live = 'live';
    case Past = 'past';

    public function label(): string
    {
        return match ($this) {
            self::Live => 'Happening now',
            default => ucfirst($this->value),
        };
    }
}
