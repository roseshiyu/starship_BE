<?php

namespace App\Actions\Course;

use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateCourse
{
    use AsAction;

    public function handle(array $params)
    {
        return DB::transaction(function () use ($params) {
            return Course::create($params);
        });
    }
}
