<?php

namespace App\Http\Controllers;

use App\Models\Deliverer;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

use \App\Models\Order;
use App\Models\OrderProduct;
use App\Models\QRCode;
use \App\Models\User;
use \App\Models\Market;

class PaymentController extends Controller
{
    use GeneralTrait;

    public function addMoney($code, $user_id)
    {
        $qrcode = QRCode::where('code', $code)->get();

        if($qrcode == null) {
            return $this->returnError(12012, 'Invalid Code');
        }

        $amount = 0;
        if (count($qrcode) > 0) {
            $amount = $qrcode[0]->amount;
        }

        $user = User::find($user_id);
        $user->wallet->amount += $amount;
        $user->save();
        $user->wallet->save();
        QRCode::where('code', $code)->delete();

        return $this->returnData('balance', $user->wallet->amount);
    }

    public function payForOrder($user_id, $price, $market_id)
    {
        $user = User::find($user_id);
        if ($user->wallet->amount >= $price) {
            $user->wallet->amount -= $price;
            //$user->save();
            $user->wallet->save();

            $market_owner = Market::find($market_id)->user;
            $market_owner->wallet->amount += $price;
            $market_owner->wallet->save();

            return $this->returnSuccessMessage("Payment completed");
        } else {
            return $this->returnError(300, "Not enough money");
        }
    }

    public function transferMoney($user1_id, $user2_id, $amount)
    {
        $user1 = User::find($user1_id);
        $user2 = User::find($user2_id);

        if ($user1->wallet->amount >= $amount) {
            $user1->wallet->amount -= $amount;
            $user1->wallet->save();
            $user2->wallet->amount += $amount;
            $user2->wallet->save();
            return $this->returnSuccessMessage("Money has been transferred");
        } else {
            return $this->returnError(300, "Not enough money");
        }
    }

    public function getUserBalance($user_id) {
        $user = User::find($user_id);
        $balance = $user->wallet->amount;
        return $this->returnData('balance', $balance);
    }
}
