<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostElectionDivision extends Model
{
    protected $table = 'post_election_division';
    protected $primaryKey = 'idpost_election_division';


    public function post(){
        return $this->belongsTo(Post::class,'idPost');
    }
}
