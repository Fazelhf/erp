<?php

declare(strict_types=1);

namespace Modules\Scheduler\Domain\Job\Enums;

enum JobStatusEnum: string
{
    case Pending   = 'pending';
    case Running   = 'running';
    case Completed = 'completed';
    case Failed    = 'failed';
    case Skipped   = 'skipped';
}
