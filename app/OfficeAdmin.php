<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfficeAdmin extends Model
{
    protected $table = 'office_admin';
    protected $primaryKey = 'idoffice_admin';

    public function user(){
        return $this->hasOne(User::class,'idUser');
    }

}
