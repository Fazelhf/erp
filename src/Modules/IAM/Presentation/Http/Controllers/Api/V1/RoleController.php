<?php

declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\IAM\Domain\Role\Entities\Role;
use Modules\IAM\Presentation\Http\Resources\RoleResource;
use Shared\Presentation\Http\Controllers\ApiController;

final class RoleController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $roles = Role::query()
            ->where('company_id', $request->user()->company_id)
            ->with('permissions')
            ->get();

        return $this->ok(RoleResource::collection($roles));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'slug'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $role = Role::create([
            'company_id'  => $request->user()->company_id,
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'description' => $data['description'] ?? null,
        ]);

        if (! empty($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }

        return $this->created(new RoleResource($role->load('permissions')));
    }

    public function show(int $id): JsonResponse
    {
        $role = Role::with('permissions')->findOrFail($id);

        return $this->ok(new RoleResource($role));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $role = Role::findOrFail($id);

        $data = $request->validate([
            'name'          => ['sometimes', 'string', 'max:100'],
            'description'   => ['nullable', 'string'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $role->update($data);

        if (isset($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }

        return $this->ok(new RoleResource($role->load('permissions')));
    }

    public function destroy(int $id): JsonResponse
    {
        Role::findOrFail($id)->delete();

        return $this->noContent();
    }
}
