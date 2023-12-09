<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\User\DTO\RegisterDTO;

class UserRepository
{
    /**
     * @param RegisterDTO $DTO
     * @return User
     */
    public function store(RegisterDTO $DTO): User
    {
        $user = new User([
            'name'  => $DTO->getName(),
            'phone' => $DTO->getPhone(),
        ]);

        $user->save();

        return $user;
    }

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
