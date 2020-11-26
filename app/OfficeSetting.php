<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfficeSetting extends Model
{
    protected $table = 'office_setting';
    protected $primaryKey = 'idoffice_setting';

    public function office(){
        return $this->hasOne(Office::class,'idoffice');
    }
}
