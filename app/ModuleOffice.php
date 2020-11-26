<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleOffice extends Model
{
    protected $table = 'office_module';
    protected $primaryKey = 'idoffice_module';

    public function office(){
        return $this->belongsTo(Office::class,'idoffice');
    }
}
