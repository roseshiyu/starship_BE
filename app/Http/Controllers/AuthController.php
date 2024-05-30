<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginAdmin;
use App\Actions\Auth\RegisterUser;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\StoreAuthRequest;
use App\Http\Requests\UpdateAuthRequest;
use App\Models\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(RegisterRequest $request)
    {
        return response()->data(RegisterUser::run($request->validated()));
    }

    /**
     * Display a listing of the resource.
     */
    public function login(LoginRequest $request)
    {
        if ($request->route()->getName() == 'admin.login') {
            $res = LoginAdmin::run($request->validated());
        } else {
            return false;
        }

        return response()->data($res);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Auth $auth)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Auth $auth)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthRequest $request, Auth $auth)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auth $auth)
    {
        //
    }
}
