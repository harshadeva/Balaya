<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeneficialCat extends Model
{
    protected $table = 'beneficial_cat';
    protected $primaryKey = 'idbeneficial_cat';

    public function post(){
        return $this->belongsTo(Post::class,'idPost');
    }

    public function category(){
        return $this->belongsTo(Category::class,'idcategory');
    }
}
