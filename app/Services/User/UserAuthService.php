<?php

namespace App\Services\User;

use App\Repositories\Messenger\DTO\IncomingSmsDTO;
use App\Repositories\User\UserRepository;

class UserAuthService
{
    public function __construct(
        protected UserRepository $repository,
    ) {
    }

    /**
     * @param IncomingSmsDTO $DTO
     * @return null|object
     */
    public function getUserByPhone(IncomingSmsDTO $DTO): ?object
    {
        $phone = $DTO->getPhone();

        return $this->repository->getUserByPhone($phone);
    }

    /**
     * @param IncomingSmsDTO $DTO
     * @return void
     */
    public function storeUserDataInSession(IncomingSmsDTO $DTO): void
    {
        session()->put(
            [
                'name'  => $DTO->getName(),
                'phone' => $DTO->getPhone(),
            ]
        );
    }
}
