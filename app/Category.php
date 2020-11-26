<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'idcategory';

    public function subCategory(){
        return $this->belongsTo(SubCategory::class,'idsub_category');
    }

    public function analysis(){
        return $this->hasMany(Analysis::class,'idcategory');
    }

    public function beneficialCat(){
        return $this->hasMany(BeneficialCat::class,'idcategory');
    }
}
