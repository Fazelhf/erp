<?php

declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\IAM\Application\Queries\GetUser\GetUserQuery;
use Modules\IAM\Application\Queries\GetUsers\GetUsersQuery;
use Modules\IAM\Presentation\Http\Resources\UserResource;
use Shared\Application\Bus\QueryBusInterface;
use Shared\Presentation\Http\Controllers\ApiController;

final class UserController extends ApiController
{
    public function __construct(private readonly QueryBusInterface $queryBus) {}

    public function index(Request $request): JsonResponse
    {
        $users = $this->queryBus->ask(new GetUsersQuery(
            companyId: $request->user()->company_id,
            search:    $request->input('search'),
            perPage:   $request->integer('per_page', 15),
        ));

        return $this->ok(UserResource::collection($users)->response()->getData(true));
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->queryBus->ask(new GetUserQuery($id));

        return $this->ok(new UserResource($user));
    }
}
