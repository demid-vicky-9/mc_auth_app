<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Repositories\Messenger\DTO\IncomingDTO;
use App\Services\CodeGenerationService;
use App\Services\Messengers\SMS\TurboSmsService;
use App\Services\Redis\RedisSmsStorageService;
use App\Services\User\SessionService;
use App\Services\User\UserAuthService;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function __construct(
        protected UserAuthService        $authService,
        protected CodeGenerationService  $codeGenerationService,
        protected RedisSmsStorageService $redisSmsStorageService,
        protected SessionService         $sessionService,
        protected TurboSmsService        $turboSmsService,
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

        $user = $this->authService->getUserByPhone($DTO->getPhone());

        if ($user == null) {
            return redirect()
                ->route('auth.register')
                ->with('error', 'User does not exist');
        }

        $this->sessionService->storeUserDataInSession($DTO, false);

        $code = $this->codeGenerationService->generateCode();
        $codeData = [
            'code'   => $code,
            'sentAt' => now()->timestamp,
        ];
        $message = "Authorization code: {$code}";

        #$this->turboSmsService->send([$data['phone']], $message);
        $this->redisSmsStorageService->setKey($data['phone'], $codeData);

        return redirect()->route('auth.confirm.sms');
    }
}
