<?php

namespace App\Enums\StudentCourseEnrollment;

enum Status: int
{
    case inactive = 0;
    case pending = 1;
    case active = 2;
}
