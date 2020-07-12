<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouncilTypes extends Model
{
    protected $table = 'council_types';
    protected $primaryKey = 'idcouncil_types';

    public function council()
    {
        return $this->hasMany(Council::class, 'idcouncil_types');
    }
}
