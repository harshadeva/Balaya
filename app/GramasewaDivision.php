<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GramasewaDivision extends Model
{
    protected $table = 'gramasewa_division';
    protected $primaryKey = 'idgramasewa_division';

    public function pollingBooth(){
        return $this->belongsTo(PollingBooth::class,'idpolling_booth');
    }
    public function villages(){
        return $this->hasMany(Village::class,'idgramasewa_division');
    }
    public function agents(){
        return $this->hasMany(Agent::class,'idgramasewa_division');
    }
    public function officeGramasewa(){
        return $this->hasMany(StaffGramasewaDivision::class,'idgramasewa_division');
    }
    public function member(){
        return $this->hasMany(Member::class,'idgramasewa_division');
    }

    public function events(){
        return $this->hasMany(Event::class,'idgramasewa_division');
    }

    public function analysis(){
        return $this->hasMany(Analysis::class,'idgramasewa_division');
    }

    public function divisionalSecretariat(){
        return $this->belongsTo(DivisionalSecretariat::class,'iddivisional_secretariat');
    }

    public function council()
    {
        return $this->belongsTo(Council::class, 'idcouncil');
    }

}
