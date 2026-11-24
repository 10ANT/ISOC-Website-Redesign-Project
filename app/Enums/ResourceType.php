<?php

namespace App\Enums;

enum ResourceType: string
{
    case Report = 'report';
    case PolicyBrief = 'policy-brief';
    case Guide = 'guide';
    case Presentation = 'presentation';
    case Research = 'research';
    case Toolkit = 'toolkit';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::PolicyBrief => 'Policy Brief',
            default => ucfirst($this->value),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Report => 'fa-file-lines',
            self::PolicyBrief => 'fa-scale-balanced',
            self::Guide => 'fa-book-open',
            self::Presentation => 'fa-person-chalkboard',
            self::Research => 'fa-flask',
            self::Toolkit => 'fa-toolbox',
            self::Other => 'fa-file',
        };
    }
}
