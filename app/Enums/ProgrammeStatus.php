<?php

namespace App\Enums;

enum ProgrammeStatus: string
{
    case Active = 'active';
    case Planned = 'planned';
    case Completed = 'completed';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
