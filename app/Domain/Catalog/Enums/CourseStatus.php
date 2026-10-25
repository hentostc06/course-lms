<?php

namespace App\Domain\Catalog\Enums;

enum CourseStatus: string
{
    case Draft = 'draft';
    case PendingReview = 'pending_review';
    case Published = 'published';
    case Rejected = 'rejected';
    case Archived = 'archived';

    public function isEditableByInstructor(): bool
    {
        return in_array($this, [self::Draft, self::Rejected], true);
    }
}
