<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupContacts extends Model
{
    protected $table = 'group_contact';
    protected $primaryKey = 'idgroup_contact';

    public function group(){
        return $this->belongsTo(SmsGroup::class,'idsms_group');
    }
}
