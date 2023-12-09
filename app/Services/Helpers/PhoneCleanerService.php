<?php

namespace App\Services\Helpers;

class PhoneCleanerService
{
    /**
     * @param string $phone
     * @return string
     */
    public function handle(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }
}
