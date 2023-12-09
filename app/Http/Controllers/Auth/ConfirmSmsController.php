<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\User\DTO\RegisterDTO;
use App\Services\Redis\RedisSmsStorageService;
use App\Services\User\UserRegisterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ConfirmSmsController extends Controller
{
    public function __construct(
        protected RedisSmsStorageService $redisSmsStorageService,
        protected UserRegisterService    $registerService,
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
        $DTO = new RegisterDTO($name, $phone);

        $result = $this->validateConfirmCode($phone, $code);

        if ($result === false) {
            return redirect()
                ->route('auth.confirm.sms')
                ->with('error', 'Wrong code!');
        }

        $user = $this->registerService->store($DTO);
        Auth::login($user);
        return redirect()->route('front.index');
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
