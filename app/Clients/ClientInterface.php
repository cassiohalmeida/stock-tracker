<?php

namespace App\Clients;

use App\Stock;

interface ClientInterface
{
    public function checkAvailability(Stock $stock): StockStatus;
}
