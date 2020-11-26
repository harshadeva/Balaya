<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeneficialDistrict extends Model
{
    protected $table = 'benefical_district';
    protected $primaryKey = 'idbenefical_district';

    public function posts(){
        return $this->belongsTo(Post::class,'idPost');
    }

    public function district(){
        return $this->belongsTo(District::class,'iddistrict');
    }

}
