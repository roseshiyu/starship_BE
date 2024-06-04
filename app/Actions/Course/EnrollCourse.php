<?php

namespace App\Actions\Course;

use App\Enums\StudentCourseEnrollment\Status;
use App\Models\StudentCourseEnrollment;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class EnrollCourse
{
    use AsAction;

    public function handle(array $params)
    {
        return DB::transaction(function () use ($params) {
            return StudentCourseEnrollment::create([
                'student_id' => $params['student_id'],
                'course_id' => $params['course_id'],
                'entry_requirement_proof' => $params['entry_requirement_proof'],
                'status' => Status::pending,
                'created_admin_id' => $params['admin_id'],
            ]);
        });
    }
}
