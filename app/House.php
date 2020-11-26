<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    protected $table = 'house';
    protected $primaryKey = 'idhouse';

    public function houseDynamics(){
        return $this->hasMany(HouseDynamic::class,'idhouse');
    }

    public function village(){
        return $this->hasMany(Village::class,'idvillage');
    }
}
