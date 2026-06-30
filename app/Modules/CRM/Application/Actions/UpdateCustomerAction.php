<?php

declare(strict_types=1);

namespace App\Modules\CRM\Application\Actions;

use App\Modules\CRM\Application\DTOs\CustomerData;
use App\Modules\CRM\Domain\Models\Customer;
use App\Modules\CRM\Infrastructure\Repositories\CustomerRepository;

final class UpdateCustomerAction
{
    public function __construct(private readonly CustomerRepository $repository) {}

    public function execute(Customer $customer, CustomerData $data): Customer
    {
        return $this->repository->update($customer, $data->toArray());
    }
}
