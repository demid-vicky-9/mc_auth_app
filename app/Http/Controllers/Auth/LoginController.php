<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Repositories\Messenger\DTO\IncomingDTO;
use App\Services\CodeGenerationService;
use App\Services\Redis\RedisSmsStorageService;
use App\Services\User\SessionService;
use App\Services\User\UserAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        protected UserAuthService        $authService,
        protected CodeGenerationService  $codeGenerationService,
        protected RedisSmsStorageService $redisSmsStorageService,
        protected SessionService         $sessionService,
    ) {
    }

    /**
     * @param UserLoginRequest $request
     * @return RedirectResponse
     */
    public function handle(UserLoginRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $DTO = new IncomingDTO(
            '',
            $data['phone'],
        );

        # проверка юзера
        $user = $this->authService->getUserByPhone($DTO->getPhone());

        if ($user == null) {
            return redirect()
                ->route('auth.register')
                ->with('error', 'User does not exist');
        }

        $this->sessionService->storeUserDataInSession($DTO, false);
        $code = $this->codeGenerationService->generateCode();

        # Відправка смс - перевіряємо, чи відправилась - записуємо код в Redis

        $this->redisSmsStorageService->setKey($data['phone'], $code);
        return redirect()->route('auth.confirm.sms');
    }
}
