<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VotersCount extends Model
{
    protected $table = 'voters_count';
    protected $primaryKey = 'idvoters_count';

    public function village(){
        return $this->belongsTo(Village::class,'idvillage');
    }

}
