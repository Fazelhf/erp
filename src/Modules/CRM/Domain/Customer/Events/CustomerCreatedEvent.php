<?php

declare(strict_types=1);

namespace Modules\CRM\Domain\Customer\Events;

use Modules\CRM\Domain\Customer\Entities\Customer;

final class CustomerCreatedEvent
{
    public function __construct(public readonly Customer $customer) {}
}
