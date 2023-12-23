<?php

namespace App\Services\User;

use App\Enums\SocialProviderEnum;
use App\Repositories\User\DTO\RegisterDTO;
use Exception;
use Illuminate\Http\RedirectResponse;
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
     * @return RedirectResponse
     */
    public function redirectToProvider(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param string $provider
     * @return RedirectResponse
     */
    public function handleProviderCallback(string $provider): RedirectResponse
    {
        try {
            $user = Socialite::driver($provider)->user();
            $socialProvider = SocialProviderEnum::from($provider);

            $DTO = new RegisterDTO(
                $user->getName(),
                null,
                $user->getEmail(),
                null,
            );
            #Log::info('User Data:', (array)$user);

            $existingUser = $this->authService->getUserByEmail($user->getEmail());

            if ($socialProvider->value === 'facebook') {
                $existingUser = $this->authService->getUserByFbId($user->getId());
            }

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
