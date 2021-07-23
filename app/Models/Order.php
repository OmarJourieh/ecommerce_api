<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Models\User;
use \App\Models\Deliverer;
use \App\Models\Product;
use \App\Models\OrderProduct;

class Order extends Model
{
    use HasFactory;
protected $guarded =[];

    public function deliverer() {
        return $this->belongsTo(Deliverer::class);
    }

    public function user() {
        return $this->belongsTo(Order::class);
    }

    public function orderProducts() {
        return $this->hasMany(OrderProduct::class);
    }

}
