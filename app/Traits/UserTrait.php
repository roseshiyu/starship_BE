<?php

namespace App\Traits;

use App\Models\Student;
use App\Models\Teacher;

trait UserTrait
{
    public function generateReferralCode($model_class)
    {
        $code = mt_rand(100000000, 999999999); // Generate a random string
        // Check if the generated code already exists in the database
        switch ($model_class) {
            case Student::class:
                while (Student::where('member_id', $code)->exists()) {
                    $code = mt_rand(100000000, 999999999); // Regenerate the code if it already exists
                }
                break;
            case Teacher::class:
                while (Teacher::where('member_id', $code)->exists()) {
                    $code = mt_rand(100000000, 999999999); // Regenerate the code if it already exists
                }
                break;

            default:
                // code...
                break;
        }

        return $code;
    }
}
