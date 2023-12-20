<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messenger\IncomingSmsRequest;
use App\Repositories\Messenger\DTO\IncomingDTO;
use App\Services\Helpers\GenerateCodeAndSendSmsService;
use App\Services\User\SessionService;
use App\Services\User\UserAuthService;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    public function __construct(
        protected UserAuthService               $authService,
        protected SessionService                $sessionService,
        protected GenerateCodeAndSendSmsService $codeAndSendSmsService,
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
                ->route('login')
                ->with('error', 'This number is already in database');
        }

        $this->sessionService->storeUserDataInSession($DTO);

        return $this->codeAndSendSmsService->handle($data['phone']);
    }
}
