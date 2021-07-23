<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Models\User;
use \App\Models\Order;
use \App\Models\DelivererRating;

class Deliverer extends Model
{
    use HasFactory;
    protected $fillable = [

        'user_id',
        'is_busy',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function delivererRatings() {
        return $this->hasMany(DelivererRating::class);
    }
}
