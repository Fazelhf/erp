<?php

declare(strict_types=1);

namespace Modules\Audit\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Audit\Infrastructure\Persistence\Models\AuditLog;
use Modules\Audit\Presentation\Http\Resources\AuditLogResource;
use Shared\Presentation\Http\Controllers\ApiController;

final class AuditLogController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $logs = AuditLog::query()
            ->where('company_id', $request->user()->company_id)
            ->when($request->input('action'), fn ($q, $v) => $q->where('action', $v))
            ->when($request->input('user_id'), fn ($q, $v) => $q->where('user_id', $v))
            ->when($request->input('model'), fn ($q, $v) => $q->where('auditable_type', $v))
            ->when($request->input('from'), fn ($q, $v) => $q->where('created_at', '>=', $v))
            ->when($request->input('to'), fn ($q, $v) => $q->where('created_at', '<=', $v))
            ->latest('created_at')
            ->paginate($request->integer('per_page', 25));

        return $this->ok(AuditLogResource::collection($logs)->response()->getData(true));
    }

    public function show(int $id): JsonResponse
    {
        $log = AuditLog::findOrFail($id);

        return $this->ok(new AuditLogResource($log));
    }
}
