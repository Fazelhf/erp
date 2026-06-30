<?php

declare(strict_types=1);

namespace Modules\Integrations\Domain\Channel\Enums;

enum IntegrationTypeEnum: string
{
    case Email        = 'email';
    case Sms          = 'sms';
    case Payment      = 'payment';
    case TaxAuthority = 'tax_authority';
    case Storage      = 'storage';
    case Ldap         = 'ldap';
    case Webhook      = 'webhook';
    case Telegram     = 'telegram';
    case Whatsapp     = 'whatsapp';
}
