<?php

declare(strict_types=1);

namespace App\Modules\CRM\Domain\Events;

use App\Modules\CRM\Domain\Models\Customer;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class CustomerCreatedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Customer $customer) {}
}
