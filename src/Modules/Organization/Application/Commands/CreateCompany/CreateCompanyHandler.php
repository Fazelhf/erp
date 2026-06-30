<?php

declare(strict_types=1);

namespace Modules\Organization\Application\Commands\CreateCompany;

use Modules\Organization\Domain\Company\Entities\Company;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class CreateCompanyHandler implements CommandHandlerInterface
{
    public function handle(object $command): Company
    {
        /** @var CreateCompanyCommand $command */
        return Company::create([
            'tenant_id'           => $command->tenantId,
            'name'                => $command->name,
            'legal_name'          => $command->legalName,
            'registration_number' => $command->registrationNumber,
            'tax_id'              => $command->taxId,
            'phone'               => $command->phone,
            'email'               => $command->email,
            'address'             => $command->address,
            'currency'            => $command->currency,
            'is_active'           => true,
        ]);
    }
}
