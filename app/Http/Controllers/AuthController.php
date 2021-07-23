<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\County;
use App\Models\Deliverer;
use App\Models\Market;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\GeneralTrait;
use http\Client\Response;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    use GeneralTrait;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);

    }//end __construct()


    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email'    => 'required',
                'password' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $token_validity = (24 * 60);

        $this->guard()->factory()->setTTL($token_validity);

        if (!$token = $this->guard()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);

    }//end login()


    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username'     => 'required|string|between:2,100',
                'email'    => 'required|email|unique:users',
                'password' => 'required|min:6',
                'account_type' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $user = User::create(
            array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            )
        );
        $user_id= User::where('email', $request->email)->get()[0]->id;
        Wallet::create([
            'amount' => 0,
            'user_id' => $user_id,
            ]);

        if($request->account_type=='market'){
            $market =Market::create([
                'user_id' => $user_id,
                'is_open' => 0
            ]);
        }elseif ($request->account_type=='delivery'){
            $delivery = Deliverer::create([
                'user_id' => $user_id,
                'is_busy' => 0,

            ]);

        }
        return response()->json(['message' => 'User created successfully', 'user' => $user]);

    }//end register()


    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'User logged out successfully']);

    }//end logout()


    public function profile()
    {

         $user= $this->guard()->user();
        //   $this->returnData('amout',Crypt::decrypt( $user->password));
        return $user;


    }//end profile()


    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());

    }//end refresh()

    public function getBalance(){
       $id = $this->guard()->user()->id;
       $amount = Wallet::where("user_id",$id)->get()[0]->amount;
       return $this->returnData('amout',$amount);

    }
    public  function getUser(){
        return $this->guard()->user();

    }

    public function getAdresses(){

        $id = $this->guard()->user()->id;
        $adresses = User::find($id)->address()->get();

        foreach ($adresses as $adresse){
            $county_name =$adresse->county()->get(['city_id','name']);
      $city =City::where("id",$county_name[0]->city_id)->get(['country_id','name']);
      $country = Country::find($city[0]->country_id)->get('name');
          $adresse["city"] =  $county_name[0]->name;
          $adresse["city_name"] =$city[0]->name;
            $adresse["country"] = $country[0]->name;
        }
        return $this->returnData('address', $adresses);
    }

    public function getCety($country_id){

        $city = City::where('country_id',$country_id)->get('name');
        return $this->returnData('City', $city);
    }

    public function geCou($City_id){

        $city = County::where('City_id',$City_id)->get('name');
        return $this->returnData('county', $city);
    }

    public function getCountr(){
        $country = Country::all('name','id');
        return $this->returnData('country',$country);
    }


    protected function respondWithToken($token)
    {
        return response()->json(
            [
                'token'          => $token,
                'token_type'     => 'bearer',
                'token_validity' => ($this->guard()->factory()->getTTL() * 60),
            ]
        );

    }//end respondWithToken()

public function uploadphoto(Request $request)
{


    if ($request->hasFile('file'))
    {
        $file = $request->file('file');
        $uploadpath = "storage/image";
        $orgenalimage = $file->getClientOriginalName();
        $file_name= time().'.'.$orgenalimage;
        $file->move($uploadpath,$file_name);
        $this->guard()->user()->update(['profile_pic'=>$file_name]);
        return response()->json(["message" => "Image Uploaded Succesfully"]);
    }
    else
    {
        return response()->json(["message" => "Select image first."]);
    }
}






    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [

                'first_name' => 'string|between:2,100',
               'last_name' => 'string|between:2,100',
                'phone' => 'string|between:2,100',
                'username' => 'string|between:2,100',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors(),
                ],
                400
            );
        }



        $this->guard()->user()->update(
            $validator->validated());
        return $this->guard()->user();


    }//end update()

public function getmarketid(){
    return $this->guard()->user()->market->id;
}


    protected function guard()
    {
        return Auth::guard();

    }//end guard()

    public function removeFromBalance($amount) {
        $id = $this->guard()->user()->id;
        $wallet = Wallet::where("user_id",$id)->get()[0];
        $wallet->amount -= $amount;
        $wallet->save();
        return $this->returnData('Remaining', $wallet->amount);
    }


}
