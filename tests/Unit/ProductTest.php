<?php

namespace Tests\Unit;

use App\Product;
use App\Retailer;
use App\Stock;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_checks_for_stock_at_retailers()
    {
        $product = factory(Product::class)->create();
        $retailer = factory(Retailer::class)->create();
        $this->assertFalse($product->inStock());

        $stock = new Stock([
            'price' => 1000,
            'url' => 'https://foo.com',
            'sku' => '12345',
            'in_stock' => true
        ]);

        $retailer->addStock($product, $stock);

        $this->assertTrue($product->inStock());
    }
}
