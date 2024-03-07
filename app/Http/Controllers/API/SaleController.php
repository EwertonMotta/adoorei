<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Http\Resources\SaleResource;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        return SaleResource::collection(Sale::all());
    }

    public function store(StoreSaleRequest $request)
    {
        try {
            DB::beginTransaction();
            $amount = 0;
            $sale = Sale::create([
                'id' => Sale::generateId(),
            ]);

            foreach ($request->products as $key => $value) {
                $product = Product::find($value['product_id']);
                $sale->products()->attach($product->id, ['quantity' => $value['quantity']]);

                $amount += $product->price * $value['quantity'];
            }

            $sale->amount = $amount;
            $sale->save();

            DB::commit();
            return new SaleResource($sale);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function show(Sale $sale)
    {
        return new SaleResource($sale);
    }

    public function update(UpdateSaleRequest $request, Sale $sale)
    {

        try {
            DB::beginTransaction();

            foreach ($request->products as $key => $value) {
                $product = Product::find($value['product_id']);
                $sale->products()->attach($product->id, ['quantity' => $value['quantity']]);

                $sale->amount += $product->price * $value['quantity'];
            }

            $sale->save();

            DB::commit();
            return new SaleResource($sale);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();

        return response()->json(['message' => 'Sale deleted successfully'], 200);
    }
}
