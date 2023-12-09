<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\User\DTO\RegisterDTO;
use App\Repositories\User\UserRepository;

class UserRegisterService
{
    public function __construct(
        protected UserRepository $repository,
    ) {
    }

    /**
     * @param RegisterDTO $DTO
     * @return User
     */
    public function store(RegisterDTO $DTO): User
    {
        return $this->repository->store($DTO);
    }
}
