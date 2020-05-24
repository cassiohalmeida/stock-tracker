<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use App\Retailer;
use App\Stock;
use Faker\Generator as Faker;

$factory->define(Stock::class, function (Faker $faker) {
    return [
        'product_id' => factory(Product::class)->create()->id,
        'retailer_id' => factory(Retailer::class)->create()->id,
        'price' => $faker->randomNumber(4),
        'sku' => $faker->unique()->creditCardNumber(),
        'url' => $faker->url(),
    ];
});

$factory->state(Stock::class, 'with-stock', function (Faker $faker) {
    return [
        'in_stock' => 1
    ];
});

$factory->state(Stock::class, 'without-stock', function (Faker $faker) {
    return [
        'in_stock' => 0
    ];
});
