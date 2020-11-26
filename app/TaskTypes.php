<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskTypes extends Model
{
    protected $table = 'task_type';
    protected $primaryKey = 'idtask_type';

    public function task(){
        return $this->hasMany(Task::class,'idtask_type');
    }
    public function taskUsers(){
        return $this->hasMany(TaskTypeUser::class,'idtask_type');
    }
}
