<?php

declare(strict_types=1);

namespace Modules\Workflow\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Workflow\Application\Services\WorkflowEngine;
use Modules\Workflow\Domain\Instance\Entities\WorkflowInstance;
use Modules\Workflow\Presentation\Http\Resources\WorkflowInstanceResource;
use Shared\Presentation\Http\Controllers\ApiController;

final class WorkflowController extends ApiController
{
    public function __construct(private readonly WorkflowEngine $engine) {}

    public function index(Request $request): JsonResponse
    {
        $instances = WorkflowInstance::query()
            ->where('company_id', $request->user()->company_id)
            ->with(['currentStep'])
            ->when($request->input('status'), fn ($q, $v) => $q->where('status', $v))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return $this->ok(WorkflowInstanceResource::collection($instances)->response()->getData(true));
    }

    public function start(Request $request): JsonResponse
    {
        $data = $request->validate([
            'definition_slug' => ['required', 'string'],
            'subject_type'    => ['required', 'string'],
            'subject_id'      => ['required', 'integer'],
            'context'         => ['nullable', 'array'],
        ]);

        $instance = $this->engine->start(
            definitionSlug: $data['definition_slug'],
            companyId:      $request->user()->company_id,
            initiatorId:    $request->user()->id,
            subjectType:    $data['subject_type'],
            subjectId:      $data['subject_id'],
            context:        $data['context'] ?? [],
        );

        return $this->created(new WorkflowInstanceResource($instance));
    }

    public function show(int $id): JsonResponse
    {
        $instance = WorkflowInstance::with(['currentStep', 'transitions'])->findOrFail($id);

        return $this->ok(new WorkflowInstanceResource($instance));
    }

    public function advance(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'decision' => ['required', 'string', 'in:approve,reject,delegate,skip'],
            'comment'  => ['nullable', 'string'],
        ]);

        $instance = WorkflowInstance::findOrFail($id);

        $instance = $this->engine->advance(
            instance:  $instance,
            actorId:   $request->user()->id,
            decision:  $data['decision'],
            comment:   $data['comment'] ?? null,
        );

        return $this->ok(new WorkflowInstanceResource($instance->load(['currentStep', 'transitions'])));
    }
}
