<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostDistrict extends Model
{
    protected $table = 'post_district';
    protected $primaryKey = 'idpost_district';

    public function post(){
        return $this->hasMany(Post::class,'idPost');
    }
}
