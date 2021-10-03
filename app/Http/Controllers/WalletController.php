<?php

namespace App\Http\Controllers;

use App\Wallet;
use Illuminate\Http\Request;
use App\User;


class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $auth = User::where('id',auth()->id())->pluck('id');
        if($auth[0] == $id){
            $wallet = Wallet::where('user_id',$id)->first();

            return response()->json([
                'data' =>[
                    'name' => $wallet->user->name,
                    'amount' => $wallet->amount,
                    'status' => $wallet->status,
                ]
            ],200);
        }else{
            return sendError('Unauthorized');
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function credit(Request $request, $id)
    {
        $auth = User::where('id',auth()->id())->pluck('id');
        if($auth[0] == $id){
            $wallet = Wallet::where('user_id',$id)->first();
            $credit = $request->amount + $wallet->amount;
            $pay = $wallet->update(['amount'=> $credit]);
            if($pay){
                transaction($request,$wallet->id,'credit','success');
                return response()->json([
                    'data' =>[
                        'message' =>'Your account has been credited',
                        'name' => $wallet->user->name,
                        'amount' => $wallet->amount,
                    ]
                ],200);
            }else{
            transaction($request,$wallet->id,'credit','failed');
                return response()->json([
                    'data' =>[
                        'message' =>'Your account could not be credited',
                        'name' => $wallet->user->name,
                        'amount' => $wallet->amount,
                    ]
                ],400);
            }
            
        }else{
            return sendError('Unauthorized');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function debit(Request $request, $id)
    {
        $auth = User::where('id',auth()->id())->pluck('id');
        if($auth[0] == $id){
            $wallet = Wallet::where('user_id',$id)->first();
            if($request->amount > $wallet->amount){
                return sendError('Insufficient Balance');
            }
            $debit = $wallet->amount - $request->amount;
            $paid = $wallet->update(['amount'=> $debit]);
            if($paid){
                transaction($request,$wallet->id,'debit','success');
                return response()->json([
                    'data' =>[
                        'message' =>'Your account has been debited',
                        'name' => $wallet->user->name,
                        'amount' => $wallet->amount,
                    ]
                ],200);
            }else{
                transaction($request,$wallet->id,'debit','failed');
                return response()->json([
                    'data' =>[
                        'message' =>'Your account could not be debited',
                        'name' => $wallet->user->name,
                        'amount' => $wallet->amount,
                    ]
                ],400);
            }
            
        }else{
            return sendError('Unauthorized');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        $auth = User::where('id',auth()->id())->pluck('id');
        if($auth[0] == $id){
            $wallet = Wallet::where('user_id',$id)->first();
            if($wallet->active){
                return response()->json([
                    'data' =>[
                        'success' => false,
                        'message' =>'Your account is already active',
                    ]
                ],200);
            }else{
                $wallet->update(['active'=> true]);
                return response()->json([
                    'data' =>[
                        'success' => true,
                        'message' =>'Your account has been activated',
                    ]
                ],200);
            }
        }else{
            return sendError('Unauthorized');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function deactivate($id)
    {
        $auth = User::where('id',auth()->id())->pluck('id');
        if($auth[0] == $id){
            $wallet = Wallet::where('user_id',$id)->first();
            if($wallet->active == false){
                return response()->json([
                    'data' =>[
                        'success' => false,
                        'message' =>'Your account is already deactivated',
                    ]
                ],200);
            }else{
                $wallet->update(['active'=> false]);
                return response()->json([
                    'data' =>[
                        'success' => true,
                        'message' =>'Your account has been activated',
                    ]
                ],200);
            }
        }else{
            return sendError('Unauthorized');
        }
    }
}
