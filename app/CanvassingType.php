<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CanvassingType extends Model
{
    protected $table = 'canvassing_type';
    protected $primaryKey = 'idcanvassing_type';

    public function canvassing(){
        return $this->hasMany(Canvassing::class,'idcanvassing');
    }
}
