<?php

namespace App\Http\Controllers;

use App\Models\Deliverer;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

use \App\Models\Order;
use App\Models\OrderProduct;
use \App\Models\User;
use \App\Models\Market;

class MarketController extends Controller
{
    use GeneralTrait;

    public function acceptOrder($id)
    {
        $order = Order::find($id);
        $order->is_accepted = 1;
        $order->save();
        return $this->returnSuccessMessage("Order has been accepted successfully!");
    }

    public function rejectOrder($id)
    {
        $order = Order::find($id);
        $order->is_accepted = 2;
        $order->save();
        if($order->is_paid == 1) {
            $user = User::find($order->user_id);
            $user->wallet->amount += $order->total;
            $user->wallet->save();
            $user->save();
        }
        return $this->returnSuccessMessage("Order has been rejected successfully!");
    }

    public function getOrdersByMarket($market_id)
    {
        $orders = Order::where('market_id', $market_id)->get();
        $orders1 = Order::where('market_id', $market_id)->get();

        $totalPrice = 0;
        foreach ($orders as $order) {

            $totalPrice = 0;
            $orderProducts = $order->orderProducts;
            foreach ($orderProducts as $op) {
                $totalPrice += $op->product->price * $op->quantity;
            }
            $order['total-price'] = $totalPrice;
        }

        foreach ($orders1 as $order) {
            $order['total-price'] = $totalPrice;
        }

        return $this->returnData('orders', $orders1);
    }

    public function getOwnerByMarketById($market_id)
    {
        $owner = Market::find($market_id)->user;

        if ($owner != null) {
            return $this->returnData('market', $owner->username);
        }
    }

    public function getProductsOfMarket($market_id)
    {
        $products = Market::find($market_id)->products;

        if ($products != null) {
            return $this->returnData('products', $products);
        }
    }

    public function getMarketIdByUserId($user_id)
    {
        $user = User::find($user_id);
        return $this->returnData('market_id', $user->market->id);
    }


    public function getAllMarket()
    {
        $market= Market::all();
        return $this->returnData('market', $market);
    }
}
