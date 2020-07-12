<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ethnicity extends Model
{
    protected $table = 'ethnicity';
    protected $primaryKey = 'idethnicity';

    public function agents(){
        return $this->hasMany(Agent::class,'idethnicity');
    }

    public function taskEthnicity(){
        return $this->hasMany(TaskEthnicity::class,'idethnicity');
    }

    public function member(){
        return $this->hasMany(Member::class,'idethnicity');
    }
}
