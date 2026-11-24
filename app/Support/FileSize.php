<?php

namespace App\Support;

/**
 * Human-readable file sizes without requiring the intl extension
 * (Laravel's Number::fileSize() depends on it).
 */
class FileSize
{
    public static function format(?int $bytes, int $precision = 1): string
    {
        $bytes = max(0, (int) $bytes);
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return ($i === 0 ? $bytes : round($bytes, $precision)).' '.$units[$i];
    }
}
