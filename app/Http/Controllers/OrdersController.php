<?php

namespace App\Http\Controllers;

use App\Models\Deliverer;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

use \App\Models\Order;
use App\Models\OrderProduct;
use \App\Models\User;

use function PHPSTORM_META\map;

class OrdersController extends Controller
{
    use GeneralTrait;

    public function getOrdersByUser($user_id)
    {
        $orders = Order::where('user_id', $user_id)->get();
        $orders1 = Order::where('user_id', $user_id)->get();

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



    public function addOrder(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required|numeric',
                'market_id' => 'required|numeric',
                'deliverer_id' => 'nullable|numeric',
                'total' => 'required|numeric',               //----------new------
                'is_paid' => 'required|numeric',               //----------new------
                'deliver_scheduel' => 'nullable',
                'address' => 'required',
                'is_accepted' => 'required',
                'is_delivered' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->returnError("101", "Invalid Data");
        } else {
            $id = Order::create($validator->validated())->id;
            return $this->returnData('id', $id);
        }
    }

    public function addOrderProduct(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'product_id' => 'required|numeric',
                'order_id' => 'required|numeric',
                'quantity' => 'required|numeric',
                'notes' => 'nullable',
            ]
        );
        if ($validator->fails()) {
            return $this->returnError("101", "Invalid Data");
        } else {
            $id = OrderProduct::create($validator->validated())->id;
            return $this->returnData('id', $id);
        }
    }

    public function getOrderProducts($orderId) {
        $orderProducts = OrderProduct::where('order_id', $orderId)->get();
        return $orderProducts;
    }


    public function deleteOrder($id)
    {
        $order= order::find($id);
        if($order->is_accepted==0){
        $result= $order->delete();
            if ($result == 1) {
                return $this->returnSuccessMessage('Product deleted successfully');
            } else {
                return $this->returnError("100", "The product does not exist");
            }
        }else{
            return $this->returnError(1,"Unable to delete accepted/rejected order");
        }

    }

    public function getUserNameById($id) {
        $user = User::find($id);
        return $this->returnData('name', $user->username);
    }



}
