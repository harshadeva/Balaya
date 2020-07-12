<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostPollingBooth extends Model
{
    protected $table = 'post_polling_booth';
    protected $primaryKey = 'idpost_polling_booth';

    public function post(){
        return $this->belongsTo(Post::class,'idPost');
    }
}
