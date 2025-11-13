<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Получить пользователя с загруженной должностью и категориями комфорта.
     *
     * @param int $userId
     * @return User|null
     */
    public function getUserWithPosition(int $userId): ?User;
}

