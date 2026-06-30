<?php

declare(strict_types=1);

namespace Modules\Inventory\Presentation\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\Inventory\Application\Commands\CreateProduct\CreateProductCommand;
use Modules\Inventory\Application\Commands\DeleteProduct\DeleteProductCommand;
use Modules\Inventory\Application\Queries\GetProducts\GetProductsQuery;
use Modules\Inventory\Domain\Product\Enums\ProductUnitEnum;
use Modules\Inventory\Presentation\Http\Requests\StoreProductRequest;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;

class ProductController extends Controller
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface   $queryBus,
    ) {}

    public function index(Request $request): View
    {
        $products = $this->queryBus->ask(new GetProductsQuery(
            companyId: auth()->user()->company_id,
            search:    $request->input('search'),
            lowStock:  (bool) $request->input('low_stock'),
        ));

        return view('inventory.products.index', [
            'products' => $products,
            'units'    => ProductUnitEnum::cases(),
        ]);
    }

    public function create(): View
    {
        return view('inventory.products.create', ['units' => ProductUnitEnum::cases()]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $v = $request->validated();

        $this->commandBus->dispatch(new CreateProductCommand(
            companyId:     auth()->user()->company_id,
            name:          $v['name'],
            unit:          $v['unit'],
            price:         (float) $v['price'],
            sku:           $v['sku'] ?? null,
            description:   $v['description'] ?? null,
            costPrice:     (float) ($v['cost_price'] ?? 0),
            stockQuantity: (float) ($v['stock_quantity'] ?? 0),
            minStockLevel: (float) ($v['min_stock_level'] ?? 0),
        ));

        return redirect()->route('products.index')->with('success', 'محصول با موفقیت ثبت شد.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->commandBus->dispatch(new DeleteProductCommand($id));

        return redirect()->route('products.index')->with('success', 'محصول حذف شد.');
    }
}
