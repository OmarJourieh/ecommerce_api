<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Deliverer;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

use \App\Models\Order;
use App\Models\OrderProduct;
use \App\Models\User;

class DelivererController extends Controller
{
    use GeneralTrait;
    public function getOrdersByDeliverer($deliverer_id)
    {
        $orders = Order::where('deliverer_id', $deliverer_id)->get();
        $orders1 = Order::where('deliverer_id', $deliverer_id)->get();

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

    public function getOrdersByDelivererUser($user_id)
    {
        $deliverer = User::find($user_id)->deliverer;
        $orders = Order::where('deliverer_id', $deliverer->id)->get();
        $orders1 = Order::where('deliverer_id', $deliverer->id)->get();

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

    public function getActiveOrder($deliverer_id)
    {
        $orders = Order::where('deliverer_id', $deliverer_id)->get();
        $active_order = null;
        $totalPrice = 0;
        foreach ($orders as $order) {

            $totalPrice = 0;
            $orderProducts = $order->orderProducts;
            foreach ($orderProducts as $op) {
                $totalPrice += $op->product->price * $op->quantity;
            }
            $order['total-price'] = $totalPrice;

            if ($order->is_delivered == 0) {
                $active_order = $order;
            }
        }

        if ($active_order == null) {
            return $this->returnSuccessMessage("No currently active orders");
        } else {
            return $this->returnData("order", $active_order);
        }
    }

    public function getProductsByOrder($order_id)
    {
        $order = Order::find($order_id);
        $products = $order->orderProducts;
        foreach ($products as $product) {
            $total_price = 0;
            $product['name'] = $product->product->name;
            $total_price += $product->product->price * $product->quantity;
            $product['total_price'] = $total_price;
        }

        return $this->returnData("products", $products);
    }

    public function confirmDelivery($order_id, $deliverer_id)
    {
        $deliverer = Deliverer::find($deliverer_id);
        $deliverer->is_busy = 0;
        $deliverer->save();

        $order = Order::find($order_id);
        $order->is_delivered = 1;
        $order->save();

        return $this->returnSuccessMessage("Confirmation submitted");
    }

    public function getDelivererName($id)
    {
        $deliverer = Deliverer::find($id)->user;
        return $this->returnData('name', $deliverer->username);
    }


    public function  getDelivererNonBusy(){
        $user = User::with('deliverer')->whereHas('deliverer',function ($q){
            $q -> where('is_busy','0');

        })->get();
        return $this->returnData('user',  $user);
    }
}
