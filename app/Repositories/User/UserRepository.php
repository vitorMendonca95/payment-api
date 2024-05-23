<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function __construct(protected User $user)
    {
    }

    public function create($userData): User
    {
        $userData['password'] = Hash::make($userData['password']);

        $this->user->fill($userData)->save();

        return $this->user;
    }
}
