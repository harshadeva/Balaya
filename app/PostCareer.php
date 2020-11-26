<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostCareer extends Model
{
    protected $table = 'post_career';
    protected $primaryKey = 'idpost_career';

    public function post()
    {
        return $this->belongsTo(Post::class, 'idPost');
    }
}
