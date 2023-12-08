<?php

namespace App\Console\Commands\Messenger;

use App\Services\Messengers\SMS\TurboSmsService;
use Illuminate\Console\Command;
use JetBrains\PhpStorm\NoReturn;

class SendTurboSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-turbo-sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send TurboSMS';

    /**
     * Execute the console command.
     */
    #[NoReturn] public function handle(TurboSmsService $service): string
    {
        $phones = ['380935367483'];
        $message = 'Test';

        dd($service->send($phones, $message));
    }
}
