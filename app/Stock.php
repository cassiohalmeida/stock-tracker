<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Stock extends Model
{
    protected $table = 'stock';

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track()
    {
        if ($this->retailer->name === "Best Buy") {
            $response = Http::get('https://api.test.com')->json();
            $this->update([
                'in_stock' => $response['available'],
                'price' => $response['price']
            ]);
        }
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }
}
