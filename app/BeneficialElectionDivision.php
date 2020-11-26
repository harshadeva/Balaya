<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeneficialElectionDivision extends Model
{
    protected $table = 'benefical_election_division';
    protected $primaryKey = 'idbenefical_election_division';

    public function post(){
        return $this->belongsTo(Post::class,'idPost');
    }
}
