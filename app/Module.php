<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'module';
    protected $primaryKey = 'idmodule';

    public function officeModule(){
        return $this->hasMany(OfficeModule::class,'idmodule');
    }
}
