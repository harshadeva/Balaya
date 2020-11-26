<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostEthnicity extends Model
{
    protected $table = 'post_ethnicity';
    protected $primaryKey = 'idpost_ethnicity';

    public function post(){
        return $this->belongsTo(Post::class,'idPost');
    }
}
