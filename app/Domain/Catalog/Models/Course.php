<?php

namespace App\Domain\Catalog\Models;

use App\Domain\Catalog\Enums\CourseLevel;
use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Domain\Instructor\Models\InstructorProfile;
use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property CourseLevel $level
 * @property CourseStatus $status
 * @property Carbon|null $published_at
 * @property-read InstructorProfile $instructor
 */
class Course extends Model
{
    /** @use HasFactory<CourseFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'instructor_id',
        'category_id',
        'title',
        'slug',
        'summary',
        'description',
        'outcomes',
        'requirements',
        'audience',
        'price_idr',
        'level',
        'language',
        'status',
        'thumbnail_url',
        'total_duration_minutes',
        'published_at',
    ];

    /** @return BelongsTo<InstructorProfile, $this> */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(InstructorProfile::class, 'instructor_id');
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** @return HasMany<CourseSection, $this> */
    public function sections(): HasMany
    {
        return $this->hasMany(CourseSection::class)->orderBy('sort_order');
    }

    /** @param Builder<Course> $query */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', CourseStatus::Published)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereRelation('instructor', 'status', InstructorStatus::Approved);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'outcomes' => 'array',
            'requirements' => 'array',
            'audience' => 'array',
            'price_idr' => 'integer',
            'level' => CourseLevel::class,
            'status' => CourseStatus::class,
            'published_at' => 'immutable_datetime',
        ];
    }
}
