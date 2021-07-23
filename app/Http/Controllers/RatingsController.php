<?php

namespace App\Http\Controllers;

use App\Models\ProductRating;
use App\Models\Deliverer;
use App\Models\DelivererRating;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

use \App\Models\Order;
use App\Models\OrderProduct;
use \App\Models\User;

class RatingsController extends Controller
{
    use GeneralTrait;

    public function rateProduct($user_id, $product_id, $rate)
    {
        $ratings = ProductRating::all();
        if ($ratings != null) {
            foreach ($ratings as $rating) {
                if ($rating->user_id == $user_id && $rating->product_id == $product_id) {
                    $id = ProductRating::where('user_id', $user_id)->where('product_id', $product_id)->get()[0]->id;
                    $productR = ProductRating::find($id);
                    $productR->rate = $rate;
                    $productR->save();
                    return $this->returnSuccessMessage("Rate has been updated");
                }
            }
        }

        $productRating = new ProductRating();
        $productRating->user_id = $user_id;
        $productRating->product_id = $product_id;
        $productRating->rate = $rate;
        $productRating->save();

        return $this->returnSuccessMessage("Product has been rated successfully");
    }

    public function rateDeliverer($user_id, $deliverer_id, $rate)
    {
        $ratings = DelivererRating::all();
        if ($ratings != null) {
            foreach ($ratings as $rating) {
                if ($rating->user_id == $user_id && $rating->deliverer_id == $deliverer_id) {
                    $id = DelivererRating::where('user_id', $user_id)->where('deliverer_id', $deliverer_id)->get()[0]->id;
                    $delivererR = DelivererRating::find($id);
                    $delivererR->rate = $rate;
                    $delivererR->save();
                    return $this->returnSuccessMessage("Rate has been updated");
                }
            }
        }

        $delivererRating = new DelivererRating();
        $delivererRating->user_id = $user_id;
        $delivererRating->deliverer_id = $deliverer_id;
        $delivererRating->rate = $rate;
        $delivererRating->save();

        return $this->returnSuccessMessage("Deliverer has been rated successfully");
    }

    public function averageRatingProduct($product_id)
    {
        $rates = ProductRating::where('product_id', $product_id)->get();
        $count = count($rates);
        $sum = 0;
        foreach ($rates as $rate) {
            $sum += $rate->rate;
        }
        $avg = $sum / $count;
        return $this->returnData('rate', $avg);
    }

    public function averageRatingDeliverer($deliverer_id)
    {
        $rates = DelivererRating::where('deliverer_id', $deliverer_id)->get();
        $count = count($rates);
        $sum = 0;
        foreach ($rates as $rate) {
            $sum += $rate->rate;
        }
        $avg = $sum / $count;
        return $this->returnData('rate', $avg);
    }
}
