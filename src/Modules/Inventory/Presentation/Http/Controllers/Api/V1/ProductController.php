<?php

declare(strict_types=1);

namespace Modules\Inventory\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Inventory\Application\Queries\GetProducts\GetProductsQuery;
use Modules\Inventory\Domain\Product\Entities\Product;
use Modules\Inventory\Presentation\Http\Resources\ProductResource;
use Shared\Application\Bus\QueryBusInterface;
use Shared\Presentation\Http\Controllers\ApiController;

final class ProductController extends ApiController
{
    public function __construct(private readonly QueryBusInterface $queryBus) {}

    public function index(Request $request): JsonResponse
    {
        $products = $this->queryBus->ask(new GetProductsQuery(
            companyId: $request->user()->company_id,
            search:    $request->input('search'),
        ));

        return $this->ok(ProductResource::collection($products)->response()->getData(true));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'sku'         => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'unit'        => ['nullable', 'string', 'max:50'],
            'price'       => ['required', 'numeric', 'min:0'],
            'cost_price'  => ['nullable', 'numeric', 'min:0'],
            'tax_rate'    => ['nullable', 'numeric', 'min:0', 'max:1'],
            'stock'       => ['nullable', 'numeric'],
        ]);

        $product = Product::create(array_merge($data, [
            'company_id' => $request->user()->company_id,
        ]));

        return $this->created(new ProductResource($product));
    }

    public function show(int $id): JsonResponse
    {
        return $this->ok(new ProductResource(Product::findOrFail($id)));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name'        => ['sometimes', 'string', 'max:255'],
            'sku'         => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'unit'        => ['nullable', 'string', 'max:50'],
            'price'       => ['sometimes', 'numeric', 'min:0'],
            'cost_price'  => ['nullable', 'numeric', 'min:0'],
            'tax_rate'    => ['nullable', 'numeric', 'min:0', 'max:1'],
            'stock'       => ['nullable', 'numeric'],
            'is_active'   => ['boolean'],
        ]);

        $product->update($data);

        return $this->ok(new ProductResource($product->fresh()));
    }

    public function destroy(int $id): JsonResponse
    {
        Product::findOrFail($id)->delete();

        return $this->noContent();
    }
}
