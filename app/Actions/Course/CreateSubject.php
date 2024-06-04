<?php

namespace App\Actions\Course;

use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateSubject
{
    use AsAction;

    public function handle(array $params)
    {
        return DB::transaction(function () use ($params) {
            $params['prerequisites'] = implode(',', $params['subject_ids']);
            unset($params['subject_ids']);
            $params['category_ids'] = implode(',', $params['category_ids']);

            return Subject::create($params);
        });
    }
}
