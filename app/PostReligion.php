<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostReligion extends Model
{
    protected $table = 'post_religion';
    protected $primaryKey = 'idpost_religion';

    public function post()
    {
        return $this->belongsTo(Post::class, 'idPost');
    }
}
