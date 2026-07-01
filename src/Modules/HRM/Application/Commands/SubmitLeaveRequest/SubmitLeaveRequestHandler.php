<?php

declare(strict_types=1);

namespace Modules\HRM\Application\Commands\SubmitLeaveRequest;

use Modules\HRM\Domain\Leave\Entities\LeaveRequest;
use Modules\HRM\Domain\Leave\Events\LeaveRequestSubmittedEvent;
use Shared\Application\Bus\EventBusInterface;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class SubmitLeaveRequestHandler implements CommandHandlerInterface
{
    public function __construct(private readonly EventBusInterface $eventBus) {}

    public function handle(object $command): LeaveRequest
    {
        /** @var SubmitLeaveRequestCommand $command */
        $request = LeaveRequest::create([
            'company_id' => $command->companyId,
            'user_id'    => $command->userId,
            'manager_id' => $command->managerId,
            'type'       => $command->type,
            'status'     => 'pending',
            'start_date' => $command->startDate,
            'end_date'   => $command->endDate,
            'reason'     => $command->reason,
        ]);

        $this->eventBus->publish(new LeaveRequestSubmittedEvent($request));

        return $request;
    }
}
