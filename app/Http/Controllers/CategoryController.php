<?php

namespace App\Http\Controllers;

use App\Models\Deliverer;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

use \App\Models\Order;
use App\Models\OrderProduct;
use \App\Models\User;
use \App\Models\Category;
use \App\Models\Product;

class CategoryController extends Controller
{
    use GeneralTrait;

    public function getAllCategories()
    {
        return Category::all();
    }

    public function addCategory(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            return $this->returnError("101", "Invalid Data");
        } else {
            $category = Category::create($validator->validated());
            return $this->returnSuccessMessage("Product added successfully");
        }
    }

    public function updateCategory(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|numeric',
                'name' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            return $this->returnError("101", "Invalid Data");
        } else {
            $category = Category::where('id', $request->id)->update($validator->validated());
            return $this->returnSuccessMessage("Product added successfully");
        }
    }

    public function deleteCategory($id)
    {
        $result = Category::destroy($id);
        return $this->returnSuccessMessage("Category deleted successfully!");
    }

    public function getProductsByCategory($id)
    {
        $products = Product::where('category_id', $id)->get();

        return $this->returnData('products', $products);
    }

    public function getProductsByCategoryAndMarket($category_id, $market_id)
    {
        $products = Product::where('category_id', $category_id)->where('market_id', $market_id)->get();

        return $this->returnData('products', $products);
    }

    public function getCategory($id)
    {
        $cat = Category::find($id);
        return $this->returnData('category', $cat);
    }
}
