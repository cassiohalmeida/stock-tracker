<?php

namespace Tests\Feature;

use App\Product;
use App\Retailer;
use App\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tracks_product_stock()
    {
        //Given
        // I have a product with stock
        $switch = Product::create(['name' => 'Nintendo Stwich']);
        $bestBy = Retailer::create(['name' => 'Best Buy']);
        $this->assertFalse($switch->inStock());

        $stock = new Stock([
            'price' => 1000,
            'url' => 'https://foo.com',
            'sku' => '12345',
            'in_stock' => false
        ]);

        $bestBy->addStock($switch, $stock);

        $this->assertFalse($stock->fresh()->in_stock);


        Http::fake(function () {
            return [
                'available' => true,
                'price' => 29900,
            ];
        });

        //When
        // I trigger the php artisan track command
        // And assuming the stock is available now
        $this->artisan('track');

        // Then
        // The stock details should be refreshed
        $this->assertTrue($stock->fresh()->in_stock);
    }
}
