<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CanvassingVillageTemp extends Model
{
    protected $table = 'canvassing_village_temp';
    protected $primaryKey = 'idcanvassing_village_temp';


    public function village(){
        return $this->belongsTo(Village::class,'idvillage');
    }
}
