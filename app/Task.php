<?php

namespace App;

use App\Api\ApiTaskAge;
use App\Api\ApiTaskCareer;
use App\Api\ApiTaskEducation;
use App\Api\ApiTaskEthnicity;
use App\Api\ApiTaskIncome;
use App\Api\ApiTaskReligion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    protected $table = 'task';
    protected $primaryKey = 'idtask';

    public function getNextNo(){
        $last = $this->whereHas('user', function($q){
            $q->where('idoffice', Auth::user()->idoffice);
        })->latest('idtask')->first();
        return  $last == null ? 1 : $last->task_no +1;
    }

    public function user(){
        return $this->belongsTo(User::class,'idUser');
    }

    public function taskTypes(){
        return $this->belongsTo(TaskTypes::class,'idtask_type');
    }

    public function assigned(){
        return $this->belongsTo(User::class,'assigned_by');
    }

    //Api relations
    public function apiEthnicities(){
        return $this->hasMany(ApiTaskEthnicity::class,'idtask');
    }

    public function apiCareers(){
        return $this->hasMany(ApiTaskCareer::class,'idtask');
    }

    public function apiEducation(){
        return $this->hasMany(ApiTaskEducation::class,'idtask');
    }

    public function apiReligion(){
        return $this->hasMany(ApiTaskReligion::class,'idtask');
    }

    public function apiIncome(){
        return $this->hasMany(ApiTaskIncome::class,'idtask');
    }

    public function apiAge(){
        return $this->hasOne(ApiTaskAge::class,'idtask');
    }
    //Api relations end

    public function ethnicities(){
        return $this->hasMany(TaskEthnicity::class,'idtask');
    }

    public function careers(){
        return $this->hasMany(TaskCareer::class,'idtask');
    }

    public function incomes(){
        return $this->hasMany(TaskIncome::class,'idtask');
    }

     public function religions(){
        return $this->hasMany(TaskReligion::class,'idtask');
    }

    public function educations(){
        return $this->hasMany(TaskEducation::class,'idtask');
    }

    public function genders(){
        return $this->hasMany(TaskGender::class,'idtask');
    }

    public function job(){
        return $this->hasMany(TaskJobSector::class,'idtask');
    }

    public function branchSociety(){
        return $this->hasMany(TaskBranchSociety::class,'idtask');
    }

    public function womensSociety(){
        return $this->hasMany(TaskWomens::class,'idtask');
    }

    public function youthSociety(){
        return $this->hasMany(TaskYouth::class,'idtask');
    }
    public function age(){
        return $this->hasOne(TaskAge::class,'idtask');
    }
}
