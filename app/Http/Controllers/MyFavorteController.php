<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GeneralTrait;

use \App\Models\Product;
use \App\Models\User;
class MyFavorteController extends Controller
{    use GeneralTrait;

    public function favorteproducts( $id )
    {
        $user =User::find($id);
        if($user) {
            $product = $user->products;
            return $this->returnData('product', $product);
        }else{
            return $this->returnError('33',$id);
        }
    }

    public  function  addtofavorte(Request $request){
        $user = User::find($request->user_id);

        if($user){
            $user->products()->syncWithoutDetaching($request->producte_id);
            return $this->returnSuccessMessage('success');
        }else{
            return $this->returnError('33','dont add');
        }


    }

    public function  deleteFavorte(Request $request){
    $user = User::find($request->user_id);
    $user->products()->detach($request->producte_id);
    // $user->delete();
}

//    public function getuser(){
//        $user = User::with(['products' => function($q){
//            $q-> select('name');
//        }])->find(1);
//
//شغاله بس مش مستخدمه
//        return $this->returnData('product', $user);
//    }


}
