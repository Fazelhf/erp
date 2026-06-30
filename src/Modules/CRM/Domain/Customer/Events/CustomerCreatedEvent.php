<?php

declare(strict_types=1);

namespace Modules\CRM\Domain\Customer\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\CRM\Domain\Customer\Entities\Customer;

final class CustomerCreatedEvent
{
    use Dispatchable;

    public function __construct(public readonly Customer $customer) {}
}
