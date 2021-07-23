<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Models\Address;
use \App\Models\City;

class County extends Model
{
    use HasFactory;

    public function addresses() {
        return $this->hasMany(Address::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }
}
