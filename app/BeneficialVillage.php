<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeneficialVillage extends Model
{
    protected $table = 'benefical_village';
    protected $primaryKey = 'idbenefical_village';

    public function post(){
        return $this->belongsTo(Post::class,'idPost');
    }
}
