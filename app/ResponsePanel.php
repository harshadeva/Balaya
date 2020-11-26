<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResponsePanel extends Model
{
    protected $table = 'response_panel';
    protected $primaryKey = 'idresponse_panel';

    public function user(){
        return $this->belongsTo(User::class,'idUser');
    }

    public function post(){
        return $this->belongsTo(Post::class,'idPost');
    }
}
