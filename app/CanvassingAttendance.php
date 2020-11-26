<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CanvassingAttendance extends Model
{
    protected $table = 'canvassing_attendence';
    protected $primaryKey = 'idcanvassing_attendence';

    public function canvassing(){
        return $this->hasMany(Canvassing::class,'idcanvassing');
    }
}
