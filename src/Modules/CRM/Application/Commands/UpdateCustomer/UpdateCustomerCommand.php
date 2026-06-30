<?php

declare(strict_types=1);

namespace Modules\CRM\Application\Commands\UpdateCustomer;

final readonly class UpdateCustomerCommand
{
    public function __construct(
        public int     $customerId,
        public string  $type,
        public string  $name,
        public ?string $nationalId,
        public ?string $phone,
        public ?string $email,
        public ?string $address,
        public bool    $isActive,
    ) {}
}
