<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CanvassingVillage extends Model
{
    protected $table = 'canvassing_village';
    protected $primaryKey = 'idcanvassing_village';

    public function canvassing(){
        return $this->belongsTo(Canvassing::class,'idcanvassing');
    }

    public function village(){
        return $this->belongsTo(Village::class,'idvillage');
    }

}
