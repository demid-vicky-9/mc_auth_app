<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Redis\RedisSmsStorageService;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ConfirmSmsController extends Controller
{
    public function __construct(
        protected RedisSmsStorageService $redisSmsStorageService,
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Request $request)
    {
        $name = session()->get('name');
        $phone = session()->get('phone');
        $code = $request->get('code');

        $res = $this->validateConfirmCode($phone, $code);

        dd($res);
    }

    /**
     * @param string $phone
     * @param int $code
     * @return bool
     */
    private function validateConfirmCode(string $phone, int $code): bool
    {
        $codeInRedis = $this->redisSmsStorageService->getKey($phone);

        return $codeInRedis !== null && $code == $codeInRedis;
    }
}
