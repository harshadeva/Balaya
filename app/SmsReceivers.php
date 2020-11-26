<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsReceivers extends Model
{
    protected $table = 'sms_recievers';
    protected $primaryKey = 'idsms_recievers';

    public function sms(){
        return $this->belongsTo(Sms::class,'idsms');
    }
}
