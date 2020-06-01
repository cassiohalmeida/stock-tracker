<?php

namespace Tests\Unit;

use App\Clients\ClientException;
use App\Product;
use App\Retailer;
use App\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_trhows_an_exception_if_a_client_is_not_found_when_tracking()
    {
        // Given a retailer without a class.
        $retailer = factory(Retailer::class)->create([
            'name' => 'Foo Retailer'
        ]);
        $product = factory(Product::class)->create();

        $stock = new Stock([
            'price' => 1000,
            'url' => 'https://foo.com',
            'sku' => '12345',
            'in_stock' => true
        ]);

        $retailer->addStock($product, $stock);

        $this->expectException(ClientException::class);

        $retailer->stock()->first()->track();
    }
}
