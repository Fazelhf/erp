<?php

declare(strict_types=1);

namespace Modules\Organization\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Organization\Domain\Branch\Entities\Branch;
use Modules\Organization\Presentation\Http\Resources\BranchResource;
use Shared\Presentation\Http\Controllers\ApiController;

final class BranchController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $branches = Branch::query()
            ->where('company_id', $request->user()->company_id)
            ->get();

        return $this->ok(BranchResource::collection($branches));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'code'            => ['nullable', 'string', 'max:50'],
            'is_headquarters' => ['boolean'],
            'phone'           => ['nullable', 'string', 'max:20'],
            'address'         => ['nullable', 'string'],
        ]);

        $branch = Branch::create(array_merge($data, [
            'company_id' => $request->user()->company_id,
        ]));

        return $this->created(new BranchResource($branch));
    }

    public function show(int $id): JsonResponse
    {
        return $this->ok(new BranchResource(Branch::findOrFail($id)));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $branch = Branch::findOrFail($id);

        $data = $request->validate([
            'name'            => ['sometimes', 'string', 'max:255'],
            'code'            => ['nullable', 'string', 'max:50'],
            'is_headquarters' => ['boolean'],
            'phone'           => ['nullable', 'string', 'max:20'],
            'address'         => ['nullable', 'string'],
            'is_active'       => ['boolean'],
        ]);

        $branch->update($data);

        return $this->ok(new BranchResource($branch->fresh()));
    }

    public function destroy(int $id): JsonResponse
    {
        Branch::findOrFail($id)->delete();

        return $this->noContent();
    }
}
