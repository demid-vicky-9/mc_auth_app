<?php

namespace App\Repositories\User\DTO;

class RegisterDTO
{
    public function __construct(
        protected ?string $name,
        protected ?string $phone,
        protected ?string $email,
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

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}
