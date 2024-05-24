<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserCreateRequest;
use App\Http\Resources\UserResource;
use App\Services\User\UserService;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    public function create(UserCreateRequest $request): UserResource
    {
        $user = $this->userService->create($request->all());

        return new UserResource($user);
    }
}
