<?php

namespace App\Actions\Auth;

use App\Enums\Student\Status;
use App\Enums\Type;
use App\Http\Resources\Auth\TokenResource;
use App\Http\Resources\Auth\UserResource;
use App\Models\Student;
use App\Traits\UserTrait;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class RegisterUser
{
    use AsAction, UserTrait;

    public function handle($params)
    {
        $user = null;

        return DB::transaction(function () use ($params, $user) {
            switch ($params['type']) {
                case Type::student->value:
                    $type_name = Type::student->name;
                    $abilities = 'api-student';
                    $user = Student::create([
                        'email' => $params['email'],
                        'password' => $params['password'],
                        'member_id' => $this->generateReferralCode(Student::class),
                        'status' => Status::active,
                        'nickname' => fake()->unique()->name(),
                    ]);
                    break;

                default:
                    // code...
                    break;
            }

            if (env('APP_ENV') == 'production') {
                $user->tokens()->delete();
            }

            $token = $user->createToken($type_name, [$abilities]);

            return [
                'auth' => new TokenResource($token),
                'user' => new UserResource($user),
            ];

            return $user;
        });
    }
}
