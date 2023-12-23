<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\User\UserRepository;

class UserAuthService
{
    public function __construct(
        protected UserRepository $repository,
    ) {
    }

    /**
     * @param string $phone
     * @return null|User
     */
    public function getUserByPhone(string $phone): ?User
    {
        return $this->repository->getUserByPhone($phone);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        return $this->repository->getUserByEmail($email);
    }

    /**
     * @param string $id
     * @return User|null
     */
    public function getUserByFbId(string $id): ?User
    {
        return $this->repository->getUserByFbId($id);
    }
}
