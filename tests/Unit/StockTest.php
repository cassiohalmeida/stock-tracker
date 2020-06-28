<?php

namespace Tests\Unit;

use App\Clients\ClientException;
use App\Clients\ClientFactory;
use App\Clients\ClientInterface;
use App\Clients\StockStatus;
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

    /** @test */
    public function it_updates_local_stock_status_after_being_tracked()
    {

        $this->app->bind(ClientFactory::class, function() {
            return new FakeClientFactory;
        });

        $retailer = factory(Retailer::class)->create([
            'name' => 'Test Retailer'
        ]);
        $product = factory(Product::class)->create();
        $stock = new Stock([
            'price' => 1000,
            'url' => 'https://foo.com',
            'sku' => '12345',
            'in_stock' => true
        ]);
        $retailer->addStock($product, $stock);

        $stock = tap(Stock::first())->track();

        $this->assertTrue($stock->in_stock);
        $this->assertEquals(9900, $stock->price);
    }
}

class FakeClientFactory 
{
    public function make()
    {
        return new FakeClient;
    }
}

class FakeClient implements ClientInterface
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        return new StockStatus($available = true, $stock = 9900);
    }
}