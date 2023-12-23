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
            'name'        => $DTO->getName(),
            'phone'       => $DTO->getPhone(),
            'email'       => $DTO->getEmail(),
            'facebook_id' => $DTO->getFacebookId(),
        ]);

        $user->save();

        return $user;
    }

    /**
     * @param string $phone
     * @return null|User
     */
    public function getUserByPhone(string $phone): ?User
    {
        return User::query()
                   ->where('phone', $phone)
                   ->firstOr(fn() => null);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        return User::query()
                   ->where('email', $email)
                   ->firstOr(fn() => null);
    }

    /**
     * @param string $id
     * @return User|null
     */
    public function getUserByFbId(string $id): ?User
    {
        return User::query()
                   ->where('facebook_id', $id)
                   ->firstOr(fn() => null);
    }
}
