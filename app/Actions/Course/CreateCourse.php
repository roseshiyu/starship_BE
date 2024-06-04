<?php

namespace App\Actions\Course;

use App\Enums\Course\Status;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateCourse
{
    use AsAction;

    public function handle(array $params)
    {
        return DB::transaction(function () use ($params) {
            $params['subject_ids'] = implode(',', $params['subject_ids']);

            return Course::create([
                'name' => $params['name'],
                'description' => $params['description'],
                'code' => $params['code'],
                'subject_ids' => $params['subject_ids'],
                'details' => null,
                'status' => Status::active,
                'category_id' => $params['category_id'],
            ]);
        });
    }
}
