<?php

declare(strict_types=1);

namespace App\Modules\HRM\Application\DTOs;

use App\Modules\HRM\Domain\Enums\LeaveTypeEnum;

final readonly class LeaveRequestData
{
    public function __construct(
        public LeaveTypeEnum $type,
        public string        $startDate,
        public string        $endDate,
        public string        $reason,
        public ?int          $managerId = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            type:       LeaveTypeEnum::from($data['type']),
            startDate:  $data['start_date'],
            endDate:    $data['end_date'],
            reason:     $data['reason'],
            managerId:  $data['manager_id'] ?? null,
        );
    }
}
