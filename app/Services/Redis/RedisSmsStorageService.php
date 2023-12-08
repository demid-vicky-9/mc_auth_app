<?php

namespace App\Services\Redis;

use Illuminate\Support\Facades\Redis;

class RedisSmsStorageService
{
    protected const EXPIRE = 300;

    /**
     * @param string $phone
     * @return string
     */
    public function getKey(string $phone): string
    {
        $redisValue = Redis::get("sms:{$phone}");

        return $redisValue ?? json_encode([]);
    }

    /**
     * @param string $phone
     * @param int $value
     * @return void
     */
    public function setKey(string $phone, int $value): void
    {
        Redis::set("sms:{$phone}", $value, "EX", self::EXPIRE);
    }
}
