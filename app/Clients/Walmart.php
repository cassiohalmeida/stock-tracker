<?php

namespace App\Clients;

use App\Clients\ClientInterface;
use App\Stock;
use Illuminate\Support\Facades\Http;

class Walmart implements ClientInterface
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        $response = Http::get('https://api.test.com')->json();

        return new StockStatus(
            $response['available'],
            $response['price']
        );
    }
}
