<?php

namespace App\Domain\Instructor\Models;

use App\Domain\Catalog\Models\Course;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Models\User;
use Database\Factories\InstructorProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property InstructorStatus $status
 */
class InstructorProfile extends Model
{
    /** @use HasFactory<InstructorProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'slug',
        'headline',
        'bio',
        'status',
        'approved_at',
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<Course, $this> */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => InstructorStatus::class,
            'approved_at' => 'immutable_datetime',
        ];
    }
}
