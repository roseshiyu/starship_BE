<?php

namespace App\Actions\Course;

use App\Models\StudentCourseEnrollment;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class VerifyEnrollCourse
{
    use AsAction;

    public function handle(array $params, StudentCourseEnrollment $target)
    {
        return DB::transaction(function () use ($params, $target) {
            $target->update([
                'verified_admin_id' => request()->user()->id,
                'status' => $params['status'],
                'remarks' => $params['remarks'] ?? null,
            ]);

            return $target;
        });
    }
}
