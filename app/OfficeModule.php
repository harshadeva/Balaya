<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfficeModule extends Model
{
    protected $table = 'office_module';
    protected $primaryKey = 'idoffice_module';

    public function office(){
        return $this->belongsTo(Office::class,'idoffice');
    }

    public function module(){
        return $this->belongsTo(Module::class,'idmodule');
    }
}
