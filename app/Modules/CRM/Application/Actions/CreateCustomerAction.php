<?php

declare(strict_types=1);

namespace App\Modules\CRM\Application\Actions;

use App\Modules\CRM\Application\DTOs\CustomerData;
use App\Modules\CRM\Domain\Events\CustomerCreatedEvent;
use App\Modules\CRM\Domain\Models\Customer;
use App\Modules\CRM\Infrastructure\Repositories\CustomerRepository;

final class CreateCustomerAction
{
    public function __construct(private readonly CustomerRepository $repository) {}

    public function execute(CustomerData $data): Customer
    {
        $customer = $this->repository->create($data->toArray());

        CustomerCreatedEvent::dispatch($customer);

        return $customer;
    }
}
