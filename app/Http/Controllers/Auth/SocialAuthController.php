<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\User\SocialAuthService;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialAuthController extends Controller
{
    public function __construct(
        protected SocialAuthService $authService,
    ) {
    }

    /**
     * @param string $provider
     * @return RedirectResponse|\Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider(string $provider): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return $this->authService->redirectToProvider($provider);
    }

    /**
     * @param string $provider
     * @return Application|Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback(string $provider
    ): Application|Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse {
        return $this->authService->handleProviderCallback($provider);
    }
}
