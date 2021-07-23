<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Models\County;
use \App\Models\Country;

class City extends Model
{
    use HasFactory;

    public function counties() {
        return $this->hasMany(County::class);
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }
}
