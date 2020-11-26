<?php
/**
 * Created by PhpStorm.
 * User: ishar
 * Date: 8/19/2018
 * Time: 1:09 PM
 */


namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_role';
    protected $primaryKey = 'iduser_role';

    public function user(){
        return $this->hasMany(User::class,'iduser_role');
    }
}
