<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Council extends Model
{
    protected $table = 'council';
    protected $primaryKey = 'idcouncil';

    public function councilType()
    {
        return $this->belongsTo(CouncilTypes::class, 'idcouncil_types');
    }

    public function gramasewaDivisions()
    {
        return $this->hasMany(GramasewaDivision::class, 'idcouncil');
    }

}
