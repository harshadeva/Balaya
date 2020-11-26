<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeneficialPollingBooth extends Model
{
    protected $table = 'benefical_polling_booth';
    protected $primaryKey = 'idbenefical_polling_booth';


    public function post(){
        return $this->belongsTo(Post::class,'idPost');
    }
}
