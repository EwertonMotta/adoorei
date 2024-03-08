<?php

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

uses(RefreshDatabase::class);

it('index returns all sales', function () {
    $response = $this->getJson(route('sales.index'));

    $response->assertOk();
    $response->assertJson(Sale::all()->toArray());
});

it('store creates new sale', function () {
    Product::factory()->count(2)->create();

    $saleData = [
        'products' => [
            ['product_id' => 1, 'quantity' => 2],
            ['product_id' => 2, 'quantity' => 3],
        ],
    ];

    $response = $this->postJson(route('sales.store'), $saleData);

    $response->assertCreated();
    $this->assertDatabaseHas('sales', ['id' => Sale::generateId() - 1]);
});

it('show returns specific sale', function () {
    $sale = Sale::factory()->create();

    $response = $this->getJson(route('sales.show', $sale));

    $response->assertOk();

    expect($response->json())->toHaveKey('data.sale_id', $sale->id);
});

it('update updates specific sale', function () {
    Product::factory()->create();
    $sale = Sale::factory()->create();
    $updateData = [
        'products' => [
            ['product_id' => 1, 'quantity' => 5],
        ],
    ];

    $response = $this->putJson(route('sales.update', $sale), $updateData);

    $sale->refresh();

    $response->assertOk();
    $this->assertDatabaseHas('sales', ['id' => $sale->id, 'amount' => $sale->amount]);
});

it('destroy deletes specific sale', function () {
    $sale = Sale::factory()->create();

    $response = $this->deleteJson(route('sales.destroy', $sale));

    $response->assertOk();
    expect($sale->exists())->toBeFalse();
});

it('store rolls back transaction on exception', function () {
    $saleData = [
        'products' => [
            ['product_id' => 999, 'quantity' => 2], // Assuming product_id 999 does not exist
        ],
    ];

    $response = $this->postJson(route('sales.store'), $saleData);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $this->assertDatabaseMissing('sales', ['id' => Sale::generateId()]);
});

it('update rolls back transaction on exception', function () {
    $sale = Sale::factory()->create();
    $updateData = [
        'products' => [
            ['product_id' => 999, 'quantity' => 5], // Assuming product_id 999 does not exist
        ],
    ];

    $response = $this->putJson(route('sales.update', $sale), $updateData);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $this->assertDatabaseHas('sales', ['id' => $sale->id]); // Check that the sale still exists
});
