<?php

declare(strict_types=1);

namespace Modules\Organization\Application\Commands\CreateCompany;

final readonly class CreateCompanyCommand
{
    public function __construct(
        public int     $tenantId,
        public string  $name,
        public string  $legalName,
        public ?string $registrationNumber,
        public ?string $taxId,
        public ?string $phone,
        public ?string $email,
        public ?string $address,
        public string  $currency = 'IRR',
    ) {}
}
