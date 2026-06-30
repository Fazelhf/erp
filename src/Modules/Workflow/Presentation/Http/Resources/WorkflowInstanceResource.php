<?php

declare(strict_types=1);

namespace Modules\Workflow\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class WorkflowInstanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'company_id'            => $this->company_id,
            'workflow_definition_id'=> $this->workflow_definition_id,
            'initiator_id'          => $this->initiator_id,
            'subject_type'          => $this->subject_type,
            'subject_id'            => $this->subject_id,
            'status'                => $this->status,
            'context'               => $this->context,
            'started_at'            => $this->started_at?->toISOString(),
            'completed_at'          => $this->completed_at?->toISOString(),
            'current_step'          => $this->whenLoaded('currentStep'),
            'transitions'           => $this->whenLoaded('transitions'),
        ];
    }
}
