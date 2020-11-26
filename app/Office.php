<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $table = 'office';
    protected $primaryKey = 'idoffice';

    public function users(){
        return $this->hasMany(User::class,'idoffice');
    }

    public function payments(){
        return $this->hasMany(Payment::class,'idoffice');
    }

    public function officeModule(){
        return $this->hasMany(OfficeModule::class,'idoffice');
    }

    public function district(){
        return $this->belongsTo(District::class,'iddistrict');
    }

    public function posts(){
        return $this->belongsTo(Post::class,'idoffice');
    }

    public function smaLimit(){
        return $this->hasOne(SmsLimit::class,'idoffice');
    }

    public function officeSetting(){
        return $this->hasOne(OfficeSetting::class,'idoffice');
    }

    public function module(){
        return $this->hasMany(ModuleOffice::class,'idoffice');
    }

    public function ANALYSIS(){
        $module = $this->module()->where('idmodule',1)->first();
        if($module != null){
            return $module->status;
        }
        return 0;
    }

    public function SMS(){
        $module = $this->module()->where('idmodule',2)->first();
        if($module != null){
            return $module->status;
        }
        return 0;
    }

    public function CANVASSING(){
        $module = $this->module()->where('idmodule',3)->first();
        if($module != null){
            return $module->status;
        }
        return 0;
    }

    public function MAP(){
        $module = $this->module()->where('idmodule',4)->first();
        if($module != null){
            return $module->status;
        }
        return 0;
    }
}
