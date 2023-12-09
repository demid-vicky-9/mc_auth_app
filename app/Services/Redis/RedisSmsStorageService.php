<?php

namespace App\Services\Redis;

use Illuminate\Support\Facades\Redis;

class RedisSmsStorageService
{
    protected const EXPIRE = 300;

    /**
     * @param string $phone
     * @return array|null
     */
    public function getKey(string $phone): ?array
    {
        $redisValue = Redis::get("sms:{$phone}");

        return $redisValue !== null ? json_decode($redisValue, true) : null;
    }

    /**
     * @param string $phone
     * @param array $value
     * @return void
     */
    public function setKey(string $phone, array $value): void
    {
        Redis::set("sms:{$phone}", json_encode($value), "EX", self::EXPIRE);
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
