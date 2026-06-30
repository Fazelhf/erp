<?php

declare(strict_types=1);

namespace App\Modules\CRM\Application\Actions;

use App\Modules\CRM\Domain\Models\Customer;
use App\Modules\CRM\Infrastructure\Repositories\CustomerRepository;

final class DeleteCustomerAction
{
    public function __construct(private readonly CustomerRepository $repository) {}

    public function execute(Customer $customer): bool
    {
        return $this->repository->delete($customer);
    }
}
