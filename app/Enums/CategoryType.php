<?php

namespace App\Enums;

enum CategoryType: string
{
    case News = 'news';
    case Event = 'event';
    case Resource = 'resource';

    public function label(): string
    {
        return match ($this) {
            self::News => 'News',
            self::Event => 'Events',
            self::Resource => 'Resource topics',
        };
    }
}
