<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_category';
    protected $primaryKey = 'idsub_category';

    public function mainCategory(){
        return $this->belongsTo(MainCategory::class,'idmain_category');
    }

    public function Categories(){
        return $this->hasMany(Category::class,'idsub_category');
    }
}
