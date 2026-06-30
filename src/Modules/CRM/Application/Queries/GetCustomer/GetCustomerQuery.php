<?php

declare(strict_types=1);

namespace Modules\CRM\Application\Queries\GetCustomer;

final readonly class GetCustomerQuery
{
    public function __construct(public int $customerId) {}
}
