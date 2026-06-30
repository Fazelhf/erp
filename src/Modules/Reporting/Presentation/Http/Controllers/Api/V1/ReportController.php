<?php

declare(strict_types=1);

namespace Modules\Reporting\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Reporting\Presentation\Http\Resources\ReportResource;
use Shared\Presentation\Http\Controllers\ApiController;

final class ReportController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $reports = \Modules\Reporting\Domain\Report\Entities\Report::query()
            ->where('company_id', $request->user()->company_id)
            ->when($request->input('type'), fn ($q, $v) => $q->where('type', $v))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return $this->ok(ReportResource::collection($reports)->response()->getData(true));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'    => ['required', 'string', 'in:income_statement,balance_sheet,cash_flow,sales,inventory,hrm'],
            'name'    => ['required', 'string', 'max:255'],
            'format'  => ['in:pdf,xlsx,csv'],
            'filters' => ['nullable', 'array'],
        ]);

        $report = \Modules\Reporting\Domain\Report\Entities\Report::create([
            'company_id'  => $request->user()->company_id,
            'created_by'  => $request->user()->id,
            'type'        => $data['type'],
            'name'        => $data['name'],
            'format'      => $data['format'] ?? 'pdf',
            'filters'     => $data['filters'] ?? [],
            'expires_at'  => now()->addDays(7),
        ]);

        return $this->created(new ReportResource($report), 'Report queued for generation');
    }

    public function show(int $id): JsonResponse
    {
        $report = \Modules\Reporting\Domain\Report\Entities\Report::findOrFail($id);

        return $this->ok(new ReportResource($report));
    }

    public function destroy(int $id): JsonResponse
    {
        \Modules\Reporting\Domain\Report\Entities\Report::findOrFail($id)->delete();

        return $this->noContent();
    }
}
