<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;


use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{

    public function getuser(Request $request){
        return response()->json(['user'=>$request->user()]);
        


    }

    public function logout(Request $request){
      
        $user =$request->user();
        $user->tokens()->delete();
        return response()->json([
            'status' => 200,
            'messege' => 'logout succesfully'
        
            ],200);

    }
    

    public function login(Request $request){

        $validator = Validator::make($request->all(),[
        
            
            "email" => "required|email",
            "password" => "required"
        
        
        ]);
        
        if($validator->fails()){
        
            return response()->json([
            'status' => 400,
            'messege' => 'bad request'
        
            ],400);
        
        }
        if(!Auth::attempt($request->only('email','password'))){

            return response()->json([
                'status' => 401,
                'messege' => 'unauthorized'
            
                ],401);
        }
      
        $user =User::where("email",$request->email)->first();
        $token = $request->user()->createToken('user=token',[$user->roles])->plainTextToken;
        Arr::add($user,'token',$token);
        return response()->json($user);
   
        }



public function register(Request $request){

$validator = Validator::make($request->all(),[

    "name" => "required",
    "email" => "required|email",
    "password" => "required|min:8"


]);

if($validator->fails()){

    return response()->json([
    'status' => 400,
    'messege' => 'bad request'

    ],400);

}

$user = new User();
$user->name = $request->name;
$user->email =$request->email;
$user->password =bcrypt($request->password);
$user->roles = "['user']";
$user-> save();

return response()->json([
    'status' => 200,
    'messege' => 'user registered'

    ]);


}







}
