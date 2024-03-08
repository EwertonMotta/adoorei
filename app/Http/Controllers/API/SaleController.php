<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Http\Resources\SaleResource;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class SaleController extends Controller
{
    public function index(): ResourceCollection
    {
        return SaleResource::collection(Sale::all());
    }

    public function store(StoreSaleRequest $request): JsonResource|JsonResponse
    {
        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'id' => Sale::generateId(),
            ]);

            $this->updateSaleAmount($sale, $request->products);

            DB::commit();

            return new SaleResource($sale);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function show(Sale $sale): JsonResource
    {
        return new SaleResource($sale);
    }

    public function update(UpdateSaleRequest $request, Sale $sale): JsonResource|JsonResponse
    {
        try {
            DB::beginTransaction();

            $this->updateSaleAmount($sale, $request->products);

            DB::commit();

            return new SaleResource($sale);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function destroy(Sale $sale): JsonResponse
    {
        $sale->delete();

        return response()->json(['message' => 'Sale deleted successfully'], 200);
    }

    private function updateSaleAmount(Sale $sale, array $products): void
    {
        foreach ($products as $value) {
            $product = Product::find($value['product_id']);
            $saleProduct = $sale->products()->find($value['product_id']);

            $sale->amount += $product->price * $value['quantity'];

            if ($saleProduct) {
                $sale->products()
                    ->updateExistingPivot($value['product_id'], ['quantity' => $saleProduct->pivot->quantity + $value['quantity']]);

                continue;
            }

            $sale->products()->attach($product->id, ['quantity' => $value['quantity']]);
        }

        $sale->save();
    }
}
