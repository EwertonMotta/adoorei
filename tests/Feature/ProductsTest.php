<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

uses(RefreshDatabase::class);
uses(WithoutMiddleware::class);

it('returns all products', function () {
    Product::factory()->count(5)->create();

    $response = $this->getJson(route('products.index'));

    $response->assertOk();
    $response->assertJsonCount(5, 'data');
});

it('returns empty collection when no products', function () {
    $response = $this->getJson(route('products.index'));

    $response->assertOk();
    $response->assertJsonCount(0, 'data');
});

it('returns products in correct format', function () {
    $product = Product::factory()->create();

    $response = $this->getJson(route('products.index'));

    $response->assertOk();
    $response->assertJsonFragment([
        'data' => [
            [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->showPrice,
            ],
        ],
    ]);
});
