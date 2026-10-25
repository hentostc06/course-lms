<?php

namespace App\Domain\Identity\Enums;

enum RoleName: string
{
    case Student = 'student';
    case Instructor = 'instructor';
    case Admin = 'admin';
    case SuperAdmin = 'super_admin';
}
