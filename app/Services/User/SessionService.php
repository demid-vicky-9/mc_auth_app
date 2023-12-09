<?php

namespace App\Services\User;

use App\Repositories\Messenger\DTO\IncomingDTO;

class SessionService
{
    /**
     * @param IncomingDTO $DTO
     * @param bool $storeName
     * @return void
     */
    public function storeUserDataInSession(IncomingDTO $DTO, bool $storeName = true): void
    {
        $data = [
            'phone' => $DTO->getPhone(),
        ];

        if ($storeName) {
            $data['name'] = $DTO->getName();
        }

        session()->put($data);
    }
}
