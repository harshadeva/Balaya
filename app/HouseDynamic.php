<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HouseDynamic extends Model
{
    protected $table = 'house_dynamic';
    protected $primaryKey = 'idhouse_dynamic';

    public function house(){
        return $this->belongsTo(House::class,'idhouse');
    }
}
