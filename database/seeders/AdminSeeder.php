<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'nickname' => 'admin',
                'email' => 'admin@starship.com',
                'password' => Hash::make('password'),
                'status' => 1,
            ],
            // more
        ];

        if ($admins) {
            collect($admins)->each(fn ($admin) => Admin::firstOrCreate($admin));
        }
    }
}
