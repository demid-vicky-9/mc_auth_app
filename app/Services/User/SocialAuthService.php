<?php

namespace App\Services\User;

use App\Repositories\User\DTO\RegisterDTO;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthService
{
    public function __construct(
        protected UserAuthService     $authService,
        protected UserRegisterService $registerService,
    ) {
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
            $DTO = new RegisterDTO(
                $user->getName(),
                null,
                $user->getEmail()
            );
            #Log::info('User Data:', (array)$user);

            $existingUser = $this->authService->getUserByEmail($user->getEmail());

            if (!$existingUser) {
                $newUser = $this->registerService->store($DTO);
                Auth::login($newUser);
            } else {
                Auth::login($existingUser);
            }

            return redirect()->route('front.index');
        } catch (Exception $e) {
            return redirect('/login')
                ->with('error', 'Authentication error via ' . $provider . ': ' . $e->getMessage());
        }
    }
}
