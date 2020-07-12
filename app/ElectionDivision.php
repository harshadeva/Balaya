<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ElectionDivision extends Model
{
    protected $table = 'election_division';
    protected $primaryKey = 'idelection_division';

    public function district(){
        return $this->belongsTo(District::class,'iddistrict');
    }

    public function pollingBooths(){
        return $this->hasMany(PollingBooth::class,'idelection_division');
    }

    public function agents(){
        return $this->hasMany(Agent::class,'idelection_division');
    }

    public function member(){
        return $this->hasMany(Member::class,'idelection_division');
    }

    public function events(){
        return $this->hasMany(Event::class,'idelection_division');
    }
    public function analysis()
    {
        return $this->hasMany(Analysis::class, 'idelection_division');
    }
}
