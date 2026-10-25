<?php

namespace App\Domain\Catalog\Models;

use App\Domain\Catalog\Enums\LessonType;
use Database\Factories\LessonFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property LessonType $type
 */
class Lesson extends Model
{
    /** @use HasFactory<LessonFactory> */
    use HasFactory;

    protected $fillable = [
        'course_section_id',
        'title',
        'summary',
        'type',
        'content',
        'duration_seconds',
        'is_preview',
        'sort_order',
    ];

    /** @return BelongsTo<CourseSection, $this> */
    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'type' => LessonType::class,
            'duration_seconds' => 'integer',
            'is_preview' => 'boolean',
        ];
    }
}
