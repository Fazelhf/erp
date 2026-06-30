<?php

declare(strict_types=1);

namespace Modules\Organization\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Organization\Domain\Department\Entities\Department;
use Modules\Organization\Presentation\Http\Resources\DepartmentResource;
use Shared\Presentation\Http\Controllers\ApiController;

final class DepartmentController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $departments = Department::query()
            ->whereHas('branch', fn ($q) => $q->where('company_id', $request->user()->company_id))
            ->with('children')
            ->whereNull('parent_id')
            ->get();

        return $this->ok(DepartmentResource::collection($departments));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'branch_id'  => ['required', 'integer', 'exists:branches,id'],
            'parent_id'  => ['nullable', 'integer', 'exists:departments,id'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'name'       => ['required', 'string', 'max:255'],
            'code'       => ['nullable', 'string', 'max:50'],
        ]);

        $department = Department::create($data);

        return $this->created(new DepartmentResource($department));
    }

    public function show(int $id): JsonResponse
    {
        return $this->ok(new DepartmentResource(Department::with('children')->findOrFail($id)));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $department = Department::findOrFail($id);

        $data = $request->validate([
            'parent_id'  => ['nullable', 'integer', 'exists:departments,id'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'name'       => ['sometimes', 'string', 'max:255'],
            'code'       => ['nullable', 'string', 'max:50'],
            'is_active'  => ['boolean'],
        ]);

        $department->update($data);

        return $this->ok(new DepartmentResource($department->fresh()));
    }

    public function destroy(int $id): JsonResponse
    {
        Department::findOrFail($id)->delete();

        return $this->noContent();
    }
}
