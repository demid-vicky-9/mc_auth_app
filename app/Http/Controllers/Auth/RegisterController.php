<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messenger\IncomingSmsRequest;
use App\Repositories\Messenger\DTO\IncomingDTO;
use App\Services\CodeGenerationService;
use App\Services\Redis\RedisSmsStorageService;
use App\Services\User\SessionService;
use App\Services\User\UserAuthService;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    public function __construct(
        protected UserAuthService        $authService,
        protected CodeGenerationService  $codeGenerationService,
        protected RedisSmsStorageService $redisSmsStorageService,
        protected SessionService         $sessionService,
    ) {
    }

    /**
     * @param IncomingSmsRequest $request
     * @return RedirectResponse
     */
    public function create(IncomingSmsRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $DTO = new IncomingDTO(
            $data['name'],
            $data['phone'],
        );

        $user = $this->authService->getUserByPhone($DTO->getPhone());

        if ($user) {
            return redirect()
                ->route('auth.login')
                ->with('error', 'This number is already in database');
        }

        $this->sessionService->storeUserDataInSession($DTO);
        $code = $this->codeGenerationService->generateCode();

        # Відправка смс - перевіряємо, чи відправилась - записуємо код в Redis

        $this->redisSmsStorageService->setKey($data['phone'], $code);
        return redirect()->route('auth.confirm.sms');
    }
}
