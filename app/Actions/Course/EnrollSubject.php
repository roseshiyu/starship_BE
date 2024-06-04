<?php

namespace App\Actions\Course;

use App\Models\StudentSubjectEnrollment;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class EnrollSubject
{
    use AsAction;

    public function handle(array $params)
    {

        return DB::transaction(function () use ($params) {
            StudentSubjectEnrollment::create([
                'student_id' => $params['student_id'],
                'subject_id' => $params['subject_id'],
                'course_id' => $params['course_id'],
                'status' => $params['status'],
            ]);

            return Subject::create($params);
        });
    }
}
