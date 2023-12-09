<?php

namespace App\Repositories\User\DTO;

class RegisterDTO
{
    public function __construct(
        protected ?string $name,
        protected ?string $phone,
    ) {
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }
}
