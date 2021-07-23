<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Models\Deliverer;
use \App\Models\User;

class DelivererRating extends Model
{
    use HasFactory;

    public function deliverer() {
        return $this->belongTo(Deliverer::class);
    }

    public function user() {
        return $this->belongTo(User::class);
    }
}
