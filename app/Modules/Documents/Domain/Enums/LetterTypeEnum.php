<?php

declare(strict_types=1);

namespace App\Modules\Documents\Domain\Enums;

enum LetterTypeEnum: string
{
    case Internal = 'internal';
    case Incoming = 'incoming';
    case Outgoing = 'outgoing';

    public function label(): string
    {
        return match($this) {
            self::Internal => 'داخلی',
            self::Incoming => 'وارده',
            self::Outgoing => 'صادره',
        };
    }
}
