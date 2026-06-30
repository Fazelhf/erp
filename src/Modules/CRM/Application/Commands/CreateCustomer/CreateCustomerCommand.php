<?php

declare(strict_types=1);

namespace Modules\CRM\Application\Commands\CreateCustomer;

final readonly class CreateCustomerCommand
{
    public function __construct(
        public int     $companyId,
        public string  $type,
        public string  $name,
        public ?string $nationalId,
        public ?string $phone,
        public ?string $email,
        public ?string $address,
    ) {}
}
