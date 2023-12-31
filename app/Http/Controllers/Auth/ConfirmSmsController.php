<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\User\DTO\RegisterDTO;
use App\Services\Redis\RedisSmsStorageService;
use App\Services\User\UserAuthService;
use App\Services\User\UserRegisterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ConfirmSmsController extends Controller
{
    public function __construct(
        protected RedisSmsStorageService $redisSmsStorageService,
        protected UserAuthService        $authService,
        protected UserRegisterService    $registerService,
    ) {
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function confirm(Request $request): RedirectResponse
    {
        $name = session()->get('name');
        $phone = session()->get('phone');
        $code = $request->get('code');
        $DTO = new RegisterDTO(
            $name,
            $phone,
            null,
            null
        );

        $codeInRedis = $this->validateConfirmCode($phone, $code);

        if ($codeInRedis === false) {
            return redirect()
                ->route('auth.confirm.sms')
                ->with('error', 'Wrong code!');
        }

        $user = $this->authService->getUserByPhone($DTO->getPhone());

        if ($user == null) {
            $newUser = $this->registerService->store($DTO);
            Auth::login($newUser);

            return redirect()->route('front.index');
        }

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

        return $codeInRedis !== null && $code == $codeInRedis['code'];
    }
}
