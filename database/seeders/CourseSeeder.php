<?php

namespace Database\Seeders;

use App\Enums\Course\Status;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Serajah Year 1',
                'description' => 'Malaysia 2017 History',
                'code' => 'SEJ2011',
                'weeks' => '12',
                'credits' => '4',
                'status' => Status::active,
            ],
            // more
        ];

        if ($courses) {
            collect($courses)->each(fn ($domain) => Course::firstOrCreate($domain));
        }
    }
}
