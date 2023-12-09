<?php

namespace App\Services\Redis;

use Illuminate\Support\Facades\Redis;

class RedisSmsStorageService
{
    protected const EXPIRE = 300;

    /**
     * @param string $phone
     * @return int|null
     */
    public function getKey(string $phone): ?int
    {
        $redisValue = Redis::get("sms:{$phone}");

        return $redisValue !== null ? (int)$redisValue : null;
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

    /**
     * @param string $phone
     * @return void
     */
    public function deleteKey(string $phone): void
    {
        Redis::del("sms:{$phone}");
    }
}
