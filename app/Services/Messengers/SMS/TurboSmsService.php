<?php

namespace App\Services\Messengers\SMS;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TurboSmsService
{
    private const URL = 'https://api.turbosms.ua/message/send.json';

    public function __construct(
        protected Client $client,
    ) {
    }

    /**
     * @param array $phones
     * @param string $message
     * @return string
     */
    public function send(array $phones, string $message): string
    {
        $token = config('messenger.turbosms.token');

        $headers = [
            'Content-Type'  => 'application/x-www-form-urlencoded',
            'Authorization' => $token,
        ];

        try {
            $response = $this->client->post(
                self::URL,
                [
                    'headers'     => $headers,
                    'form_params' => $this->arrayParams($phones, $message),
                ]
            );

            return $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param array $phones
     * @param string $message
     * @return array
     */
    private function arrayParams(array $phones, string $message): array
    {
        return [
            'recipients' => $phones,
            'sms'        => [
                'sender' => 'TAXI',
                'text'   => $message
            ]
        ];
    }
}
