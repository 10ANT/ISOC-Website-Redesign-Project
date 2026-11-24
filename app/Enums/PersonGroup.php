<?php

namespace App\Enums;

enum PersonGroup: string
{
    case Board = 'board';
    case Officer = 'officer';
    case Founding = 'founding';
    case HallOfFame = 'hall-of-fame';

    public function label(): string
    {
        return match ($this) {
            self::Board => 'Board of Directors',
            self::Officer => 'Officers',
            self::Founding => 'Founding Members',
            self::HallOfFame => 'Hall of Fame',
        };
    }
}
