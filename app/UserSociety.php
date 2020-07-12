<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSociety extends Model
{
    protected $table = 'user_society';
    protected $primaryKey = 'iduser_society';

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }
}
