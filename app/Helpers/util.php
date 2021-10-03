<?php
use App\Transaction;
use Illuminate\Support\Str;

function sendError($error, $errorMessages = [], $code = 400)
{
    $response = [
        'success' => false,
        'message' => $error,
    ];

    if(!empty($errorMessages)){
        $response['data'] = $errorMessages;
    }


    return response()->json($response, $code);
}

function transaction($request, $wallet_id, $type, $status){
    $time = now();
    $ref = str_replace(array('-',':',' '),'',$time);
    $code = 'Ref-'.$ref;
    $transaction = new Transaction();
    $transaction->wallet_id = $wallet_id;
    $transaction->reference_code = $code;
    $transaction->transaction_type = $type;
    $transaction->amount = $request->amount;
    $transaction->status = $status;
    $transaction->save();
}