<?php

declare(strict_types=1);

namespace Modules\Workflow\Domain\Step\Enums;

enum StepTypeEnum: string
{
    case Approval    = 'approval';
    case Notification = 'notification';
    case Automation  = 'automation';
    case Condition   = 'condition';
    case Delay       = 'delay';
}
