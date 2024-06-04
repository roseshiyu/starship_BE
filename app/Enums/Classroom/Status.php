<?php

namespace App\Enums\Classroom;

enum Status: int
{
    case ontime = 1;
    case late = 2;
    case absent = 3;
    case unexcused_absence = 4;
}
