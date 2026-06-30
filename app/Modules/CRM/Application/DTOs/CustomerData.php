<?php

declare(strict_types=1);

namespace App\Modules\CRM\Application\DTOs;

final readonly class CustomerData
{
    public function __construct(
        public string  $name,
        public ?string $companyName,
        public ?string $email,
        public ?string $phone,
        public ?string $mobile,
        public ?string $address,
        public ?string $nationalId,
        public ?string $economicCode,
        public ?string $notes,
        public bool    $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name:         $data['name'],
            companyName:  $data['company_name'] ?? null,
            email:        $data['email'] ?? null,
            phone:        $data['phone'] ?? null,
            mobile:       $data['mobile'] ?? null,
            address:      $data['address'] ?? null,
            nationalId:   $data['national_id'] ?? null,
            economicCode: $data['economic_code'] ?? null,
            notes:        $data['notes'] ?? null,
            isActive:     (bool) ($data['is_active'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'name'          => $this->name,
            'company_name'  => $this->companyName,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'mobile'        => $this->mobile,
            'address'       => $this->address,
            'national_id'   => $this->nationalId,
            'economic_code' => $this->economicCode,
            'notes'         => $this->notes,
            'is_active'     => $this->isActive,
        ];
    }
}
