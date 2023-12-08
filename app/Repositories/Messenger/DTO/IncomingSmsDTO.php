<?php

namespace App\Repositories\Messenger\DTO;

class IncomingSmsDTO
{
    public function __construct(
        protected string $name,
        protected string $phone,
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }
}
