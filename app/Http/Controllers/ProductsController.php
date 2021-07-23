<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

use \App\Models\Product;

class ProductsController extends Controller
{
    use GeneralTrait;
    public function getProduct($id)
    {
        $product = Product::find($id);
        // return response()->json($product);
        return $this->returnData('product', $product);
    }

    public function getAllProducts()
    {
        $products = Product::all();
        return $this->returnData('products', $products);
    }

    public function deleteProduct($id)
    {
        $result = Product::destroy($id);
        if ($result == 1) {
            return $this->returnSuccessMessage('Product deleted successfully');
        } else {
            return $this->returnError("100", "The product does not exist");
        }
    }
    public function uploadphoto(Request $request)
    {


        if ($request->hasFile('file'))
        {
            $file = $request->file('file');
            $uploadpath = "storage/image";
            $orgenalimage = $file->getClientOriginalName();
            $file_name= $orgenalimage;
            $file->move($uploadpath,$file_name);
            return response()->json(["message" => "Image Uploaded Succesfully"]);
        }
        else
        {
            return response()->json(["message" => "Select image first."]);
        }
    }
    public function addProduct(Request $request)
    {
        // $product = new Product();
        $validator = Validator::make(
            $request->all(),
            [
                'market_id' => 'required|numeric',
                'category_id' => 'required|numeric',
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|numeric',
                'is_offer' => 'nullable',
                'picture' => 'required'
            ]
        );
        if ($validator->fails()) {
            return $this->returnError("101", "Invalid Data");
        } else {
            $product = Product::create($validator->validated());
            return $this->returnSuccessMessage("Product added successfully");
        }
    }

    public function updateProduct($id, Request $request)
    {

        // $product = new Product();
        $validator = Validator::make(
            $request->all(),
            [
                'market_id' => 'required|numeric',
                'category_id' => 'required|numeric',
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|numeric',
                'is_offer' => 'nullable',
                'picture' => 'required'
            ]
        );
        if ($validator->fails()) {
            return $this->returnError("101", "Invalid Data");
        } else {
            $product = Product::where('id', $id)->update($validator->validated());
            return $this->returnData("product", $product);
        }
    }
}
