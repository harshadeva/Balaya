<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostVillage extends Model
{
    protected $table = 'post_village';
    protected $primaryKey = 'idpost_village';

    public function post(){
        return $this->belongsTo(Post::class,'idPost');
    }
}
