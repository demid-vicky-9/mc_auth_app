<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Models\User;
use App\Repositories\Messenger\DTO\IncomingDTO;
use App\Services\Helpers\GenerateCodeAndSendSmsService;
use App\Services\Redis\RedisSmsStorageService;
use App\Services\User\SessionService;
use App\Services\User\UserAuthService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    protected const EX_REDIS = 300;

    public function __construct(
        protected UserAuthService               $authService,
        protected SessionService                $sessionService,
        protected RedisSmsStorageService        $redisSmsStorageService,
        protected GenerateCodeAndSendSmsService $codeAndSendSmsService,
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

        $redirectResponse = $this->checkLastEnter($data['phone']);

        if ($redirectResponse) {
            return $redirectResponse;
        }

        return $this->codeAndSendSmsService->handle($data['phone']);
    }

    /**
     * @param string $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
     */
    public function redirectToProvider(string $provider
    ): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param string $provider
     * @return \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application
     */
    public function handleProviderCallback(string $provider
    ): \Illuminate\Foundation\Application|Redirector|RedirectResponse|Application {
        try {
            $user = Socialite::driver($provider)->user();

            #Log::info('User Data:', (array)$user);

            $existingUser = User::where('email', $user->getEmail())->first();

            if ($existingUser) {
                Auth::login($existingUser);
            } else {
                // Create new user --> to service+repository!
                $newUser = new User([
                    'name'  => $user->getName(),
                    'email' => $user->getEmail(),
                ]);
                $newUser->save();

                Auth::login($newUser);
            }

            return redirect()->route('front.index');
        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Authentication error via ' . $provider . ': ' . $e->getMessage());
        }
    }

    /**
     * @param string $key
     * @return RedirectResponse|bool
     */
    private function checkLastEnter(string $key): RedirectResponse|bool
    {
        $lastSentTime = $this->redisSmsStorageService->getKey($key);

        if ($lastSentTime && now()->timestamp - $lastSentTime['sentAt'] < self::EX_REDIS) {
            return redirect()
                ->back()
                ->with('error', 'Please wait 5 minutes before trying again.');
        }

        return false;
    }
}
