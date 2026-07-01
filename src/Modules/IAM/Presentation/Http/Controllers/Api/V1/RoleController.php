<?php

declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\IAM\Application\Commands\CreateRole\CreateRoleCommand;
use Modules\IAM\Application\Commands\DeleteRole\DeleteRoleCommand;
use Modules\IAM\Application\Commands\UpdateRole\UpdateRoleCommand;
use Modules\IAM\Application\Queries\GetRole\GetRoleQuery;
use Modules\IAM\Application\Queries\GetRoles\GetRolesQuery;
use Modules\IAM\Presentation\Http\Resources\RoleResource;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;
use Shared\Presentation\Http\Controllers\ApiController;

final class RoleController extends ApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface   $queryBus,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $roles = $this->queryBus->ask(new GetRolesQuery($request->user()->company_id));

        return $this->ok(RoleResource::collection($roles));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'slug'          => ['required', 'string', 'max:100'],
            'description'   => ['nullable', 'string'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $role = $this->commandBus->dispatch(new CreateRoleCommand(
            companyId:     $request->user()->company_id,
            name:          $data['name'],
            slug:          $data['slug'],
            description:   $data['description'] ?? null,
            permissionIds: $data['permissions'] ?? [],
        ));

        return $this->created(new RoleResource($role));
    }

    public function show(int $id): JsonResponse
    {
        $role = $this->queryBus->ask(new GetRoleQuery($id));

        return $this->ok(new RoleResource($role));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name'          => ['sometimes', 'string', 'max:100'],
            'description'   => ['nullable', 'string'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $role = $this->commandBus->dispatch(new UpdateRoleCommand(
            roleId:        $id,
            name:          $data['name'] ?? null,
            description:   $data['description'] ?? null,
            permissionIds: $data['permissions'] ?? null,
        ));

        return $this->ok(new RoleResource($role));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteRoleCommand($id));

        return $this->noContent();
    }
}
