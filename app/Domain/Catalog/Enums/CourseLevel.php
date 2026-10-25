<?php

namespace App\Domain\Catalog\Enums;

enum CourseLevel: string
{
    case AllLevels = 'all_levels';
    case Beginner = 'beginner';
    case Intermediate = 'intermediate';
    case Advanced = 'advanced';

    public function label(): string
    {
        return match ($this) {
            self::AllLevels => 'Semua tingkat',
            self::Beginner => 'Pemula',
            self::Intermediate => 'Menengah',
            self::Advanced => 'Lanjutan',
        };
    }
}
