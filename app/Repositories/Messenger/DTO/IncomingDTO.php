<?php

namespace App\Repositories\Messenger\DTO;

class IncomingDTO
{
    public function __construct(
        protected ?string $name,
        protected string  $phone,
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
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }
}
