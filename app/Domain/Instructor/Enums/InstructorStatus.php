<?php

namespace App\Domain\Instructor\Enums;

enum InstructorStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Suspended = 'suspended';
}
