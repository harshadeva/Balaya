<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    protected $table = 'sms';
    protected $primaryKey = 'idsms';

    public function user(){
        return $this->belongsTo(User::class,'idUser');
    }

    public function receivers(){
        return $this->hasMany(SmsReceivers::class,'idsms');
    }
}
