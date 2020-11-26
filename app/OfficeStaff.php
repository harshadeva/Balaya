<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfficeStaff extends Model
{
    protected $table = 'office_staff';
    protected $primaryKey = 'idoffice_staff';

    public function user(){
        return $this->hasOne(User::class,'idUser');
    }

//    public function officeGramasewa(){
//        return $this->hasMany(StaffGramasewaDivision::class,'idoffice_staff');
//    }


}
