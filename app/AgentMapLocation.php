<?php

namespace App;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;

class AgentMapLocation extends Model
{
    protected $table = 'agent_map_location';
    protected $primaryKey = 'idagent_map_location';

    public function user(){
        return $this->belongsTo(User::class,'idUser');
    }
}
