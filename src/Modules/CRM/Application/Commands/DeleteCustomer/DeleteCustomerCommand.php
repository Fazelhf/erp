<?php

declare(strict_types=1);

namespace Modules\CRM\Application\Commands\DeleteCustomer;

final readonly class DeleteCustomerCommand
{
    public function __construct(public int $customerId) {}
}
