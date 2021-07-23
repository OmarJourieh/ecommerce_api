<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Models\Order;
use \App\Models\Product;

class OrderProduct extends Model
{
    use HasFactory;

    public function order() {
        return $this->belongTo(Order::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    protected $guarded = [];

}
