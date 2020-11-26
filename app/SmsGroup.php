<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsGroup extends Model
{
    protected $table = 'sms_group';
    protected $primaryKey = 'idsms_group';

    public function contacts(){
        return $this->hasOne(GroupContacts::class,'idsms_group');
    }
}
