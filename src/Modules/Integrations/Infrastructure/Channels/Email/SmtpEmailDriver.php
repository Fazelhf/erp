<?php

declare(strict_types=1);

namespace Modules\Integrations\Infrastructure\Channels\Email;

use Illuminate\Support\Facades\Mail;
use Modules\Integrations\Domain\Channel\Contracts\ChannelDriverInterface;

final class SmtpEmailDriver implements ChannelDriverInterface
{
    public function __construct(private readonly array $config) {}

    public function send(array $payload): bool
    {
        try {
            Mail::raw($payload['body'], function ($message) use ($payload) {
                $message->to($payload['to'])
                        ->subject($payload['subject'] ?? '(no subject)');

                if (!empty($payload['cc'])) {
                    $message->cc($payload['cc']);
                }
            });

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    public function isConfigured(): bool
    {
        return !empty($this->config['host']) && !empty($this->config['username']);
    }
}
