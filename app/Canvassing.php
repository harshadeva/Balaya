<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Canvassing extends Model
{
    protected $table = 'canvassing';
    protected $primaryKey = 'idcanvassing';

    public function village(){
        return $this->hasMany(CanvassingVillage::class,'idcanvassing');
    }

    public function attendance(){
        return $this->hasMany(CanvassingAttendance::class,'idcanvassing');
    }

    public function type(){
        return $this->belongsTo(CanvassingType::class,'idcanvassing_type');
    }

    public function attendanceForecasting(){
        return $this->hasMany(CanvassingAttendanceForecasting::class,'idcanvassing');
    }

    public function nextCanvassingNo($officeId){
       $firstRecord =  $this->where('idoffice',$officeId)->latest()->first();
       if($firstRecord == null || $firstRecord->canvassingNo == null){
           return 1;
       }
       return intval($firstRecord->canvassingNo) + 1;
    }
}
