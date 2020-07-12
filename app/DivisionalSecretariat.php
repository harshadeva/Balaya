<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DivisionalSecretariat extends Model
{
    protected $table = 'divisional_secretariat';
    protected $primaryKey = 'iddivisional_secretariat';

    public function divisions(){
        return $this->hasMany(GramasewaDivision::class,'iddivisional_secretariat');
    }

}
