<?php

namespace App\Domain\Catalog\Enums;

enum LessonType: string
{
    case Video = 'video';
    case Article = 'article';
    case Document = 'document';
}
