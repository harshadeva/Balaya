<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeneficialGramasewaDivision extends Model
{
    protected $table = 'benefical_gramasewa_division';
    protected $primaryKey = 'idbenefical_gramasewa_division';

    public function post(){
        return $this->belongsTo(Post::class,'idPost');
    }
}
