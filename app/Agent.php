<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $table = 'agent';
    protected $primaryKey = 'idagent';


    public function numberOfMembers(){
        return MemberAgent::where('idagent',$this->idagent)->where('status',1)->count();
    }

    public function numberOfSmsMembers(){
        return MemberAgent::whereHas('member',function ($q){
            $q->where('isSms',1);
        })->where('idagent',$this->idagent)->where('status',1)->count();
    }

    public function numberOfAppMembers(){
        return MemberAgent::whereHas('member',function ($q){
            $q->where('isSms',0);
        })->where('idagent',$this->idagent)->where('status',1)->count();
    }

    public function getMembers(){
        return MemberAgent::with(['member','member.belongsUser'])->where('idagent',$this->idagent)->where('status',1)->get();
    }

    public function getSmsMembers(){
        return MemberAgent::with(['member','member.belongsUser'])->whereHas('member',function ($q){
            $q->where('isSms',1);
        })->where('idagent',$this->idagent)->where('status',1)->get();
    }

    public function getAppMembers(){
        return MemberAgent::with(['member','member.belongsUser'])->whereHas('member',function ($q){
            $q->where('isSms',0);
        })->where('idagent',$this->idagent)->where('status',1)->get();
    }

    public function user(){
        return $this->hasOne(User::class,'idUser');
    }

    public function userBelongs(){
        return $this->belongsTo(User::class,'idUser');
    }

    public function electionDivision(){
        return $this->belongsTo(ElectionDivision::class,'idelection_division');
    }

    public function pollingBooth(){
        return $this->belongsTo(PollingBooth::class,'idpolling_booth');
    }

    public function gramasewaDivision(){
        return $this->belongsTo(GramasewaDivision::class,'idgramasewa_division');
    }

    public function village(){
        return $this->belongsTo(Village::class,'idvillage');
    }

    public function religion(){
        return $this->belongsTo(Religion::class,'idreligion');
    }

    public function career(){
        return $this->belongsTo(Career::class,'idcareer');
    }

    public function ethnicity(){
        return $this->belongsTo(Ethnicity::class,'idethnicity');
    }

    public function natureOfIncome(){
        return $this->belongsTo(NatureOfIncome::class,'idnature_of_income');
    }

    public function educationalQualification(){
        return $this->belongsTo(EducationalQualification::class,'ideducational_qualification');
    }

    public function memberAgents(){
        return $this->hasMany(MemberAgent::class,'idagent');
    }
}
