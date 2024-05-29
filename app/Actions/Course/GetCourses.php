<?php

namespace App\Actions\Course;

use App\Models\Course;
use App\Traits\DynamicPagination;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCourses
{
    use AsAction, DynamicPagination;

    public function handle($params)
    {
        $query = Course::query()
            ->exact(['status', 'credit', 'weeks'], $params)
            ->like(['code'], $params)
            ->orderBy('id', 'desc');

        return $this->pagination($query);
    }
}
