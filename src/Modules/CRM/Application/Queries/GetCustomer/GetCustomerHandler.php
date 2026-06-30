<?php

declare(strict_types=1);

namespace Modules\CRM\Application\Queries\GetCustomer;

use Modules\CRM\Domain\Customer\Entities\Customer;
use Modules\CRM\Domain\Customer\Exceptions\CustomerNotFoundException;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetCustomerHandler implements QueryHandlerInterface
{
    public function handle(object $query): Customer
    {
        /** @var GetCustomerQuery $query */
        return Customer::find($query->customerId)
            ?? throw CustomerNotFoundException::withId($query->customerId);
    }
}
