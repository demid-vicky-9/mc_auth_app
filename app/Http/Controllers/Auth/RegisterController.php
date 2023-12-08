<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messenger\IncomingSmsRequest;
use App\Repositories\Messenger\DTO\IncomingSmsDTO;
use App\Services\CodeGenerationService;
use App\Services\Redis\RedisSmsStorageService;
use App\Services\User\UserAuthService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct(
        protected UserAuthService        $authService,
        protected CodeGenerationService  $codeGenerationService,
        protected RedisSmsStorageService $redisSmsStorageService,
    ) {
    }

    public function create(IncomingSmsRequest $request)
    {
        $data = $request->validated();

        $DTO = new IncomingSmsDTO(
            $data['name'],
            $data['phone'],
        );

        $user = $this->authService->getUser($DTO);

        if ($user) {
            return redirect()
                ->route('auth.login')
                ->with('error', 'This number is already in database');
        }

        $this->authService->storeUserDataInSession($DTO);
        $code = $this->codeGenerationService->generateCode();

        # Відправка смс - перевіряємо, чи відправилась - записуємо код в Redis

        $this->redisSmsStorageService->setKey($data['phone'], $code);
        return redirect()->route('auth.confirm.sms');
        # Повернення на сторінку вер-ції

        #return redirect()->route('auth.login')->with('success', 'User registered successfully!');
    }
}
