<?php

declare(strict_types=1);

namespace Modules\CRM\Application\Commands\CreateCustomer;

use Modules\CRM\Domain\Customer\Entities\Customer;
use Modules\CRM\Domain\Customer\Events\CustomerCreatedEvent;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class CreateCustomerHandler implements CommandHandlerInterface
{
    public function handle(object $command): Customer
    {
        /** @var CreateCustomerCommand $command */
        $customer = Customer::create([
            'company_id'  => $command->companyId,
            'type'        => $command->type,
            'name'        => $command->name,
            'national_id' => $command->nationalId,
            'phone'       => $command->phone,
            'email'       => $command->email,
            'address'     => $command->address,
            'is_active'   => true,
        ]);

        CustomerCreatedEvent::dispatch($customer);

        return $customer;
    }
}
