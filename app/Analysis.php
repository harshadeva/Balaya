<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Analysis extends Model
{
    protected $table = 'analysis';
    protected $primaryKey = 'idpost_category';

    public function category(){
        return $this->belongsTo(Category::class,'idcategory');
    }
    public function mainCategory(){
        return $this->belongsTo(MainCategory::class,'idmain_category');
    }

    public function response()
    {
        return $this->belongsTo(PostResponse::class, 'referrence_id');
    }
    public function electionDivision()
    {
        return $this->belongsTo(ElectionDivision::class, 'idelection_division');
    }

    public function pollingBooth()
    {
        return $this->belongsTo(PollingBooth::class, 'idpolling_booth');
    }

    public function gramasewaDivision()
    {
        return $this->belongsTo(GramasewaDivision::class, 'idgramasewa_division');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'idvillage');
    }
}
