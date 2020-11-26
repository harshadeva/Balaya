<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsLimit extends Model
{
    protected $table = 'sms_limit';
    protected $primaryKey = 'idsms_limit';

    public function office(){
        return $this->hasOne(Office::class,'idoffice');
    }


}
