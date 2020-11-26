<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostGramasewaDivision extends Model
{
    protected $table = 'post_gramasewa_division';
    protected $primaryKey = 'idpost_gramasewa_division';

    public function post()
    {
        return $this->belongsTo(Post::class, 'idPost');
    }

}
