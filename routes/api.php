<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DelivererController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RatingsController;
use App\Http\Controllers\MyFavorteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('uploadphoto', [ProductsController::class,'uploadphoto']);///////newww
Route::get("/products/{id}", [ProductsController::class, 'getProduct']);

Route::get("/products", [ProductsController::class, 'getAllProducts']);

Route::post("/products/delete/{id}", [ProductsController::class, 'deleteProduct']);

Route::post("/products/add-product", [ProductsController::class, 'addProduct']);

Route::post("/products/update-product/{id}", [ProductsController::class, 'updateProduct']);


Route::get("/orders/user/{user_id}", [OrdersController::class, 'getOrdersByUser']);

Route::get("/order-prods", [OrdersController::class, 'testFunction']);

Route::post("/orders/add-order", [OrdersController::class, 'addOrder']);

Route::post("/orders/delete-order/{id}", [OrdersController::class, 'deleteOrder']);



Route::post("/orders/add-order-product", [OrdersController::class, 'addOrderProduct']);
Route::get("/orders/get-order-products/{orderId}", [OrdersController::class, 'getOrderProducts']);
Route::get("/deliverer/name/{deliverer_id}", [DelivererController::class, 'getDelivererName']);




Route::get("/deliverer/orders/{deliverer_id}", [DelivererController::class, 'getOrdersByDeliverer']);

Route::get("/deliverer/orders/user/{user_id}", [DelivererController::class, 'getOrdersByDelivererUser']);
Route::get('/user/name/{user_id}', [OrdersController::class, 'getUserNameById']);
Route::get("/market/id/user/{user_id}", [MarketController::class, 'getMarketIdByUserId']);

Route::get("/deliverer/products/order/{order_id}", [DelivererController::class, 'getProductsByOrder']);


Route::post("/deliverer/confirm/{order_id}/{deliverer_id}", [DelivererController::class, 'confirmDelivery']);

Route::get("/deliverer/order/active/{deliverer_id}", [DelivererController::class, 'getActiveOrder']);


Route::get("/deliverer/getDelivererNonBusy", [DelivererController::class, 'getDelivererNonBusy']);



Route::get("/admin/orders", [AdminController::class, 'getAllOrders']);
Route::get("/admin/orders/active", [AdminController::class, 'getNonDeliveredOrders']);
Route::get("/admin/orders/no-deliverer", [AdminController::class, 'getOrderWithoutDelivery']);
Route::post("/admin/order/assign-deliverer/{orderId}/{delivererId}", [AdminController::class, 'assignDeliverer']);

Route::post("/admin/qr/generate/{count}/{amount}", [AdminController::class, 'generateCodes']);
Route::post("/admin/money/deduct/{market_id}/{amount}", [AdminController::class, 'deductMoney']);



//new new new new new new new new new new new new new new new new new new new new
Route::get("/admin/allcodes", [AdminController::class, 'getAllCodes']);
//new new new new new new new new new new new new new new new new new new new new





Route::post("/market/accept-order/{id}", [MarketController::class, 'acceptOrder']);

Route::post("/market/reject-order/{id}", [MarketController::class, 'rejectOrder']);

Route::get("/market/orders/{market_id}", [MarketController::class, 'getOrdersByMarket']);

Route::get("/market/{market_id}", [MarketController::class, 'getOwnerByMarketById']);

Route::get("/market/products/{market_id}", [MarketController::class, 'getProductsOfMarket']);

Route::get("/markets", [MarketController::class, 'getAllMarket']);


Route::get("/category", [CategoryController::class, 'getAllCategories']);

Route::get("/category/{id}", [CategoryController::class, 'getCategory']);/////////////new

Route::post("/category/add", [CategoryController::class, 'addCategory']);

Route::post("/category/update", [CategoryController::class, 'updateCategory']);

Route::post("/category/delete/{id}", [CategoryController::class, 'deleteCategory']);

Route::get("/category/products/{id}", [CategoryController::class, 'getProductsByCategory']);

Route::get("/category/products/{category_id}/{market_id}", [CategoryController::class, 'getProductsByCategoryAndMarket']);


Route::post("/rate/product/{user_id}/{product_id}/{rate}", [RatingsController::class, 'rateProduct']);
Route::post("/rate/deliverer/{user_id}/{deliverer_id}/{rate}", [RatingsController::class, 'rateDeliverer']);

Route::get("/rate/product/avg/{product_id}", [RatingsController::class, 'averageRatingProduct']);
Route::get("/rate/deliverer/avg/{product_id}", [RatingsController::class, 'averageRatingDeliverer']);



Route::post("/payment/add/{code}/{user_id}", [PaymentController::class, 'addMoney']);

Route::post("/payment/pay/{user_id}/{price}/{market_id}", [PaymentController::class, 'payForOrder']);

Route::post("/payment/transfer/{user1_id}/{user2_id}/{amount}", [PaymentController::class, 'transferMoney']);

Route::post("/payment/user-balance/{user_id}", [PaymentController::class, 'getUserBalance']);




Route::post("/addtofavorte", [MyFavorteController::class, 'addtofavorte']);
Route::post("/deletefavorte", [MyFavorteController::class, 'deleteFavorte']);
Route::post("/favorteproducts/{id}", [MyFavorteController::class, 'favorteproducts']);



Route::group(
    [
        'middleware' => 'api',
        'namespace'  => 'App\Http\Controllers',
        'prefix'     => 'auth',
    ],
    function ($router) {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::post('logout', 'AuthController@logout');
        Route::get('profile', 'AuthController@profile');
        Route::post('refresh', 'AuthController@refresh');
        Route::get('getBalance', 'AuthController@getBalance');
        Route::get('getAdresses', 'AuthController@getAdresses');
        Route::get('getCity/{country_id}', 'AuthController@getCety');
        Route::get('getCounty/{City_id}', 'AuthController@geCou');
        Route::get('getCountry', 'AuthController@getCountr');
        Route::get('getUser', 'AuthController@getUser');
        Route::post('update', 'AuthController@update');
        Route::get('getmarketid', 'AuthController@getmarketid');
        Route::post('uploadphoto', 'AuthController@uploadphoto');

        Route::post('removeMoney/{amount}', 'AuthController@removeFromBalance');
    }
);



Route::group(
    [
        'middleware' => ['api','checkPassword'],
        'namespace'  => 'App\Http\Controllers',
        'prefix'     => 'auth',
    ],
    function ($router) {


    });
