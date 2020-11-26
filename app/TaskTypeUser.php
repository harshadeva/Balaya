<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskTypeUser extends Model
{
    protected $table = 'task_type_user';
    protected $primaryKey = 'idtask_type_user';

    public function taskType(){
        return $this->belongsTo(TaskTypes::class,'idtask_type');
    }
}
