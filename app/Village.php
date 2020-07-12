<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Village extends Model
{
    protected $table = 'village';
    protected $primaryKey = 'idvillage';

    public function gramasewaDivision(){
        return $this->belongsTo(GramasewaDivision::class,'idgramasewa_division');
    }
    public function agents(){
        return $this->hasMany(Agent::class,'idvillage');
    }
    public function houses(){
        return $this->hasMany(House::class,'idvillage');
    }
    public function member(){
        return $this->hasMany(Member::class,'idvillage');
    }

    public function events(){
        return $this->hasMany(Event::class,'idvillage');
    }

    public function analysis()
    {
        return $this->belongsTo(Analysis::class, 'idvillage');
    }

    public function votes(){
        return $this->hasOne(VotersCount::class,'idvillage');
    }


    public function canvassingVillage(){
        return $this->belongsTo(CanvassingVillage::class,'idvillage');
    }

    public function canvassingTemp(){
        return $this->hasMany(CanvassingVillageTemp::class,'idvillage');
    }

    public function getTotalVotersCount(){
       $voters =  $this->votes;
       if($voters != null){
           return intval($voters->total);
       }
       else{
           return 0;
       }
    }

    public function getFavourableVotersCount(){
        $voters =  $this->votes;
        if($voters != null){
            return intval($voters->forecasting);
        }
        else{
            return 0;
        }
    }

    public function getFavourableElderCount($canvassingId = null){
        if($canvassingId == null){
            $canvassing = Canvassing::where('idoffice',Auth::user()->idoffice)->where('status',3)->first();
            if($canvassing == null){
                return 0;
            }
            $canvassingId = $canvassing->idcanvassing;
        }
       return HouseDynamic::where('idcanvassing',$canvassingId)->where('idhouse_member',1)->where('status',1)->where('idvoting_condition',1)->sum('count');
    }

    public function getTotalElderCount($canvassingId = null){
        if($canvassingId == null){
            $canvassing = Canvassing::where('idoffice',Auth::user()->idoffice)->where('status',3)->first();
            if($canvassing == null){
                return 0;
            }
            $canvassingId = $canvassing->idcanvassing;
        }
        return HouseDynamic::where('idcanvassing',$canvassingId)->where('idhouse_member',1)->where('status',1)->sum('count');
    }

    public function getFavourableYoungCount($canvassingId = null){
        if($canvassingId == null){
            $canvassing = Canvassing::where('idoffice',Auth::user()->idoffice)->where('status',3)->first();
            if($canvassing == null){
                return 0;
            }
            $canvassingId = $canvassing->idcanvassing;
        }
        return HouseDynamic::where('idcanvassing',$canvassingId)->where('idhouse_member',2)->where('status',1)->where('idvoting_condition',1)->sum('count');
    }

    public function getTotalYoungCount($canvassingId = null){
        if($canvassingId == null){
            $canvassing = Canvassing::where('idoffice',Auth::user()->idoffice)->where('status',3)->first();
            if($canvassing == null){
                return 0;
            }
            $canvassingId = $canvassing->idcanvassing;
        }
        return HouseDynamic::where('idcanvassing',$canvassingId)->where('idhouse_member',2)->where('status',1)->sum('count');
    }

    public function getFavourableFirstCount($canvassingId = null){
        if($canvassingId == null){
            $canvassing = Canvassing::where('idoffice',Auth::user()->idoffice)->where('status',3)->first();
            if($canvassing == null){
                return 0;
            }
            $canvassingId = $canvassing->idcanvassing;
        }
        return HouseDynamic::where('idcanvassing',$canvassingId)->where('idhouse_member',3)->where('status',1)->where('idvoting_condition',1)->sum('count');
    }

    public function getTotalFirstCount($canvassingId = null){
        if($canvassingId == null){
            $canvassing = Canvassing::where('idoffice',Auth::user()->idoffice)->where('status',3)->first();
            if($canvassing == null){
                return 0;
            }
            $canvassingId = $canvassing->idcanvassing;
        }
        return HouseDynamic::where('idcanvassing',$canvassingId)->where('idhouse_member',3)->where('status',1)->sum('count');
    }
}
