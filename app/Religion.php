<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
    protected $table = 'religion';
    protected $primaryKey = 'idreligion';

    public function agents(){
        return $this->hasMany(Agent::class,'idreligion');
    }

    public function taskReligion(){
        return $this->belongsTo(TaskReligion::class,'idreligion');
    }
    public function member(){
        return $this->hasMany(Member::class,'idreligion');
    }
}
