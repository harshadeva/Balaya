<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupSms extends Model
{
    protected $table = 'group_sms';
    protected $primaryKey = 'idgroup_sms';

    public function group(){
        return $this->belongsTo(SmsGroup::class,'idsms_group');
    }
}
