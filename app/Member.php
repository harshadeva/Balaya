<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'member';
    protected $primaryKey = 'idmember';

    public function user(){
        return $this->hasOne(User::class,'idUser');
    }

    public function userBelongs(){
        return $this->belongsTo(User::class,'idUser');
    }

    public function currentOffice(){
     return  Agent::find(intval($this->current_agent))->userBelongs->idoffice;
    }
    public function belongsUser(){
        return $this->belongsTo(User::class,'idUser');
    }

    public function memberAgents(){
        return $this->hasMany(MemberAgent::class,'idmember');
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

}
