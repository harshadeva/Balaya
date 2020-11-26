<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CanvassingAttendanceForecasting extends Model
{
    protected $table = 'canvassing_attendance_forecasting';
    protected $primaryKey = 'idcanvassing_attendance_forecasting';

    public function canvassing(){
        return $this->belongsTo(Canvassing::class,'idcanvassing');
    }
}
