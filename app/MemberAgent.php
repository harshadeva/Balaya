<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberAgent extends Model
{
    protected $table = 'member_agent';
    protected $primaryKey = 'idmember_agent';

    public function member(){
        return $this->belongsTo(Member::class,'idmember');
    }

     public function agent(){
        return $this->belongsTo(Agent::class,'idagent');
    }


}
