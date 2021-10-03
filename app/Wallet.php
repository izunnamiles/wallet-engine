<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function transact(){
        return $this->belongsTo(Transaction::class);
    }
}
