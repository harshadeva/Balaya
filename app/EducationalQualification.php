<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EducationalQualification extends Model
{
    protected $table = 'educational_qualification';
    protected $primaryKey = 'ideducational_qualification';

    public function agents(){
        return $this->hasMany(Agent::class,'ideducational_qualification');
    }

    public function taskEducation(){
        return $this->hasMany(TaskEducation::class,'ideducational_qualification');
    }

    public function member(){
        return $this->hasMany(Member::class,'ideducational_qualification');
    }
}
