<?php

declare(strict_types=1);

namespace Modules\Workflow\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Workflow\Application\Commands\AdvanceWorkflow\AdvanceWorkflowCommand;
use Modules\Workflow\Application\Commands\StartWorkflow\StartWorkflowCommand;
use Modules\Workflow\Application\Queries\GetWorkflowInstance\GetWorkflowInstanceQuery;
use Modules\Workflow\Application\Queries\GetWorkflowInstances\GetWorkflowInstancesQuery;
use Modules\Workflow\Presentation\Http\Resources\WorkflowInstanceResource;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;
use Shared\Presentation\Http\Controllers\ApiController;

final class WorkflowController extends ApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface   $queryBus,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $instances = $this->queryBus->ask(new GetWorkflowInstancesQuery(
            companyId: $request->user()->company_id,
            status:    $request->input('status'),
            perPage:   $request->integer('per_page', 15),
        ));

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

        $instance = $this->commandBus->dispatch(new StartWorkflowCommand(
            definitionSlug: $data['definition_slug'],
            companyId:      $request->user()->company_id,
            initiatorId:    $request->user()->id,
            subjectType:    $data['subject_type'],
            subjectId:      $data['subject_id'],
            context:        $data['context'] ?? [],
        ));

        return $this->created(new WorkflowInstanceResource($instance));
    }

    public function show(int $id): JsonResponse
    {
        $instance = $this->queryBus->ask(new GetWorkflowInstanceQuery($id));

        return $this->ok(new WorkflowInstanceResource($instance));
    }

    public function advance(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'decision' => ['required', 'string', 'in:approve,reject,delegate,skip'],
            'comment'  => ['nullable', 'string'],
        ]);

        $instance = $this->commandBus->dispatch(new AdvanceWorkflowCommand(
            instanceId: $id,
            actorId:    $request->user()->id,
            decision:   $data['decision'],
            comment:    $data['comment'] ?? null,
        ));

        return $this->ok(new WorkflowInstanceResource($instance->load(['currentStep', 'transitions'])));
    }
}
