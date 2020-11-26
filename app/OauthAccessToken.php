<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthAccessToken extends Model
{
    protected $table = 'oauth_access_tokens';
    protected $primaryKey = 'id';

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
