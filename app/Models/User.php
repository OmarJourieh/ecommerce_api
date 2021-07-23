<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use \App\Models\Wallet;
use \App\Models\Address;
use \App\Models\UserPreference;
use \App\Models\Message;
use \App\Models\Market;
use \App\Models\Deliverer;
use \App\Models\ProductRating;
use \App\Models\Order;
use \App\Models\DelivererRating;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'username',
//        'email',
//        'password',
//        'phone',
//    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function wallet() {
        return $this->hasOne(Wallet::class);
    }

    public function address() {
        return $this->hasMany(Address::class);
    }

    public function preferences() {
        return $this->hasMany(UserPreference::class);
    }

    public function sentMessages() {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages() {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function market() {
        return $this->hasOne(Market::class);
    }

    public function deliverer() {
        return $this->hasOne(Deliverer::class);
    }

    public function productRatings() {
        return $this->hasMany(ProductRating::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function deliverers() {
        return $this->belongsToMany(Deliverer::class);
    }

    public function delivererRatings() {
        return $this->hasMany(DelivererRating::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class,'my_favortes','user_id','product_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
