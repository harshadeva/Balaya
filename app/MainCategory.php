<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    protected $table = 'main_category';
    protected $primaryKey = 'idmain_category';

    public function subCategories(){
        return $this->hasMany(SubCategory::class,'idmain_category');
    }

    public function analysis(){
        return $this->hasMany(Analysis::class,'idcategory');
    }
}
