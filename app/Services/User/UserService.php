<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\User\UserRepository;

class UserService
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function create($userData): User
    {
        return $this->userRepository->create($userData);
    }

    public function retrieveUser(int $userId): ?User
    {
        return $this->userRepository->getUserById($userId);
    }
}
