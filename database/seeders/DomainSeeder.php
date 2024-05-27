<?php

namespace Database\Seeders;

use App\Enums\Domain\Status;
use App\Models\Domain;
use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $domains = [
            [
                'name' => 'starship',
                'status' => Status::active,
            ],
            // more
        ];

        if ($domains) {
            collect($domains)->each(fn ($domain) => Domain::firstOrCreate($domain));
        }
    }
}
