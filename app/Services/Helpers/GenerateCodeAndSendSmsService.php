<?php

namespace App\Services\Helpers;

use App\Services\CodeGenerationService;
use App\Services\Messengers\SMS\TurboSmsService;
use App\Services\Redis\RedisSmsStorageService;
use Illuminate\Http\RedirectResponse;

class GenerateCodeAndSendSmsService
{
    public function __construct(
        protected CodeGenerationService  $codeGenerationService,
        protected RedisSmsStorageService $redisSmsStorageService,
        protected TurboSmsService        $turboSmsService,
    ) {
    }

    /**
     * @param string $phone
     * @return RedirectResponse
     */
    public function handle(string $phone): RedirectResponse
    {
        $code = $this->codeGenerationService->generateCode();
        $codeData = [
            'code'   => $code,
            'sentAt' => now()->timestamp,
        ];
        $message = "Authorization code: {$code}";

        #$this->turboSmsService->send([$phone], $message);
        $this->redisSmsStorageService->setKey($phone, $codeData);

        return redirect()->route('auth.confirm.sms');
    }
}
