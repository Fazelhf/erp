<?php

declare(strict_types=1);

namespace Modules\CRM\Application\Commands\DeleteCustomer;

use Modules\CRM\Domain\Customer\Entities\Customer;
use Modules\CRM\Domain\Customer\Exceptions\CustomerNotFoundException;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class DeleteCustomerHandler implements CommandHandlerInterface
{
    public function handle(object $command): bool
    {
        /** @var DeleteCustomerCommand $command */
        $customer = Customer::find($command->customerId)
            ?? throw CustomerNotFoundException::withId($command->customerId);

        return (bool) $customer->delete();
    }
}
