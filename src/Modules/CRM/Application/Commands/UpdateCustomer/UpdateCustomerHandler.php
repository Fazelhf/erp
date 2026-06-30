<?php

declare(strict_types=1);

namespace Modules\CRM\Application\Commands\UpdateCustomer;

use Modules\CRM\Domain\Customer\Entities\Customer;
use Modules\CRM\Domain\Customer\Exceptions\CustomerNotFoundException;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class UpdateCustomerHandler implements CommandHandlerInterface
{
    public function handle(object $command): Customer
    {
        /** @var UpdateCustomerCommand $command */
        $customer = Customer::find($command->customerId)
            ?? throw CustomerNotFoundException::withId($command->customerId);

        $customer->update([
            'type'        => $command->type,
            'name'        => $command->name,
            'national_id' => $command->nationalId,
            'phone'       => $command->phone,
            'email'       => $command->email,
            'address'     => $command->address,
            'is_active'   => $command->isActive,
        ]);

        return $customer->fresh();
    }
}
