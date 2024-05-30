<?php

namespace App\Actions\Auth;

use App\Http\Resources\Auth\TokenResource;
use App\Http\Resources\Auth\UserResource;
use App\Models\Admin;
use App\Traits\UserTrait;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;

class LoginAdmin
{
    use AsAction, UserTrait;

    public function handle($params)
    {
        $admin = Admin::where('email', $params['email'])->first();

        if (! $admin || ! Hash::check($params['password'], $admin->password)) {
            abort(422, __('auth.failed'));
        }

        $token = $admin->createToken('admin', ['*']);

        return [
            'auth' => new TokenResource($token),
            'user' => new UserResource($admin),
        ];
    }
}
