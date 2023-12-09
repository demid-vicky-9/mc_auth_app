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
}
