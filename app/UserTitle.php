<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTitle extends Model
{
    protected $table = 'user_title';
    protected $primaryKey = 'iduser_title';

    public function user(){
        return $this->hasMany(User::class,'iduser_title');
    }
}
