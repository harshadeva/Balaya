<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';
    protected $primaryKey = 'idevent';

    public function user(){
        return $this->belongsTo(User::class,'idUser');
    }

    public function analysis(){
        return $this->hasMany(Analysis::class,'idcategory');
    }

    public function district(){
        return $this->belongsTo(District::class,'iddistrict');
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
}
