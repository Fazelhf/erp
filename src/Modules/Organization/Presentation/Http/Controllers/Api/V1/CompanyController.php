<?php

declare(strict_types=1);

namespace Modules\Organization\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Organization\Domain\Company\Entities\Company;
use Modules\Organization\Presentation\Http\Resources\CompanyResource;
use Shared\Presentation\Http\Controllers\ApiController;

final class CompanyController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $company = Company::find($request->user()->company_id);

        return $this->ok(new CompanyResource($company));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $company = Company::findOrFail($id);

        $data = $request->validate([
            'name'              => ['sometimes', 'string', 'max:255'],
            'legal_name'        => ['sometimes', 'string', 'max:255'],
            'phone'             => ['nullable', 'string', 'max:20'],
            'email'             => ['nullable', 'email'],
            'website'           => ['nullable', 'url'],
            'address'           => ['nullable', 'string'],
            'fiscal_year_start' => ['nullable', 'date_format:m-d'],
        ]);

        $company->update($data);

        return $this->ok(new CompanyResource($company->fresh()));
    }
}
