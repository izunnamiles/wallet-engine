<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Wallet;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return sendError('Validation Error.', $validator->errors());
        }

        $password = bcrypt($request->password);

        $data = [
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> $password
        ];
    
        $user = User::create($data);

        $wallet = Wallet::create([
            'user_id' => $user->id,
            'status' => 'inactive'
        ]);

        $token = $user->createToken('myApp')->accessToken;

        return response([ 'user' => $user, 'token' => $token]);
    }

    public function login(Request $request)
    {
        if(request()->isMethod('post')){
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            if($validator->fails()){
                return sendError('Validation Error.', $validator->errors());
            }  
            $data = [
                'email'=> $request->email,
                'password'=>$request->password
            ];
    
            if (!Auth::attempt($data)) {
                return response()->json([
                    'message' => 'Incorrect Credentials'
                ]);
            }
            //$user = User::find(auth()->id());
    
            $token = auth()->user()->createToken('myApp')->accessToken;
    
            return response(['user' => auth()->user(), 'token' => $token]); 
        }
        return response()->json([
            'message' => 'unauthorized'
        ], 401);

    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}

