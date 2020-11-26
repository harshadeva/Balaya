<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'idpayment';

    public function office(){
        return $this->belongsTo(Office::class,'idoffice');
    }

    public function nextDate(){

    }
}
