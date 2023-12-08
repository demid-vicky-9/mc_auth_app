<?php

namespace App\Services;

class CodeGenerationService
{
    protected const MIN = 1111;
    protected const MAX = 9999;

    public function generateCode(): int
    {
        return rand(self::MIN, self::MAX);
    }
}
