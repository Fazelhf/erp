<?php

declare(strict_types=1);

namespace Modules\Integrations\Infrastructure\Channels\Sms;

use Illuminate\Support\Facades\Http;
use Modules\Integrations\Domain\Channel\Contracts\ChannelDriverInterface;

final class KavenegarSmsDriver implements ChannelDriverInterface
{
    private const API_URL = 'https://api.kavenegar.com/v1/{apikey}/sms/send.json';

    public function __construct(private readonly array $config) {}

    public function send(array $payload): bool
    {
        $response = Http::post(
            str_replace('{apikey}', $this->config['api_key'], self::API_URL),
            [
                'receptor' => $payload['to'],
                'message'  => $payload['body'],
                'sender'   => $this->config['sender'] ?? null,
            ]
        );

        return $response->successful();
    }

    public function isConfigured(): bool
    {
        return !empty($this->config['api_key']);
    }
}
