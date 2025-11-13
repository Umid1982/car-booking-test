<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param int $userId
     * @return User|null
     */
    public function getUserWithPosition(int $userId): ?User
    {
        return User::with('position.comfortCategories')->find($userId);
    }
}
