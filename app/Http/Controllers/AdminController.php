<?php

namespace App\Http\Controllers;

use App\Models\Deliverer;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use \App\Models\Order;
use App\Models\OrderProduct;
use App\Models\QRCode;
use \App\Models\User;
use \App\Models\Market;

class AdminController extends Controller
{
    use GeneralTrait;
    public function getAllOrders()
    {
        $orders = Order::all();
        foreach ($orders as $order) {
            $user = User::find($order->user_id);
            if($user != null ){
                $order['user_name']= $user->username;
                $deliverer = Deliverer::find($order->deliverer_id);
                if ($deliverer != null) {
                    $order['delivery_name'] = $deliverer->user->username;
                }
            }

        }

        return $this->returnData('orders', $orders);
    }

    public function assignDeliverer($orderId, $delivererId)
    {
        $order = Order::findOrFail($orderId);
        if ($order->is_accepted) {
            $order->deliverer_id = $delivererId;
            $order->save();
            $deliverer = Deliverer::find($delivererId);
            $deliverer->is_busy = 1;
            $deliverer->save();
            return $this->returnSuccessMessage("Order has been assigned");
        } else {
            return $this->returnError(700, "Order has not been accepted");
        }
    }

    public function getNonDeliveredOrders()
    {
        $orders = Order::where('is_delivered', 0)->get();
        foreach ($orders as $order) {
            $order['user_name'] = User::find($order->user_id)->username;
            $deliverer = Deliverer::find($order->deliverer_id);
            if ($deliverer != null) {
                $order['delivery_name'] = $deliverer->user->username;
            }
        }

        return $this->returnData('orders', $orders);
    }

    public function getOrderWithoutDelivery()
    {
        $orders = Order::where('deliverer_id', null)->get();
        foreach ($orders as $order) {
            $order['user_name'] = User::find($order->user_id)->username;
        }

        return $this->returnData('orders', $orders);
    }

    private function isUnique($code)
    {
        $currentCodes = QRCode::all();
        foreach ($currentCodes as $current) {
            if ($current->code == $code) {
                return false;
            }
        }
        return true;
    }

    public function generateCodes($count, $amount)
    {
        //$code = Str::random(10);
        for ($i = 0; $i < $count; $i++) {
            $code = Str::random(10);

            while ($this->isUnique($code) == false) {
                $code = Str::random(10);
            }

            $qr = new QRCode();
            $qr->code = $code;
            $qr->amount = $amount;
            $qr->save();
        }

        return $this->returnSuccessMessage("QR Code added successfully");
    }

    public function deductMoney($market_id, $amount)
    {
        $market_owner = Market::find($market_id)->user;
        $deducted_amount = $amount;
        if($market_owner->wallet->amount < $amount) {
            $deducted_amount = $market_owner->wallet->amount;
        }
        $market_owner->wallet->amount -= $deducted_amount;
        $market_owner->wallet->save();

        return $this->returnSuccessMessage("Deducted " . $deducted_amount . " from the account");
    }


    public function getAllCodes() {
        $codes = QRCode::all();
        return $this->returnData('codes', $codes);
    }

}
