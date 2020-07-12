<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskReligion extends Model
{
    protected $table = 'task_religion';
    protected $primaryKey = 'idtask_religion';

    public function task(){
        return $this->belongsTo(Task::class,'idtask');
    }

    public function religion(){
        return $this->belongsTo(Religion::class,'idreligion');
    }
}
