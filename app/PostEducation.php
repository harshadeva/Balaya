<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostEducation extends Model
{
    protected $table = 'post_education';
    protected $primaryKey = 'idpost_education';

    public function post()
    {
        return $this->belongsTo(Post::class, 'idPost');
    }
}
