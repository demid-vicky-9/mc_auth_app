<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository
{
    /**
     * @param string $phone
     * @return null|object
     */
    public function getUserByPhone(string $phone): ?object
    {
        return User::query()
                   ->where('phone', $phone)
                   ->first();
    }
}
