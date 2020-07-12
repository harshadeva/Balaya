<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskAge extends Model
{
    protected $table = 'task_age';
    protected $primaryKey = 'idtask_age';

    public function task(){
        return $this->hasOne(Task::class,'idtask');
    }

}
