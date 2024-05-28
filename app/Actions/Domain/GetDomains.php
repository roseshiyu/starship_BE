<?php

namespace App\Actions\Domain;

use App\Enums\Domain\Status;
use App\Models\Domain;
use App\Traits\DynamicPagination;
use Lorisleiva\Actions\Concerns\AsAction;

class GetDomains
{
    use AsAction, DynamicPagination;

    public function handle($params)
    {
        $query = Domain::query()
            ->where('status', Status::active)
            ->orderBy('id', 'desc');

        return $this->pagination($query);
    }
}
