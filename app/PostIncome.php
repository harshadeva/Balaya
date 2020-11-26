<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostIncome extends Model
{
    protected $table = 'post_income';
    protected $primaryKey = 'idpost_income';

    public function post()
    {
        return $this->belongsTo(Post::class, 'idPost');
    }
}
