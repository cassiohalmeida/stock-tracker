<?php

namespace Tests\Unit;

use App\Product;
use App\Retailer;
use App\Stock;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_checks_for_stock_ar_retailers()
    {
        $switch = Product::create(['name' => 'Nintendo Stwich']);
        $bestBy = Retailer::create(['name' => 'Best Buy']);
        $this->assertFalse($switch->inStock());

        $stock = new Stock([
            'price' => 1000,
            'url' => 'https://foo.com',
            'sku' => '12345',
            'in_stock' => true
        ]);

        $bestBy->addStock($switch, $stock);

        $this->assertTrue($switch->inStock());
    }
}
