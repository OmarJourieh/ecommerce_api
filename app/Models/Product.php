<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Models\Category;
use \App\Models\Market;
use \App\Models\ProductRating;
use \App\Models\Order;
use \App\Models\OrderProduct;

class Product extends Model
{
    use HasFactory;

    public function market() {
        return $this->belongsTo(Market::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function productRatings() {
        return $this->hasMany(ProductRating::class);
    }

    public function orderProducts() {
        return $this->hasMany(OrderProduct::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class,'my_favortes','product_id','user_id');
    }
    protected $guarded = [];
}
