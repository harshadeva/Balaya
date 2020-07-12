<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskGender extends Model
{
    protected $table = 'task_gender';
    protected $primaryKey = 'idtask_gender';

    public function task(){
        return $this->belongsTo(Task::class,'idtask');
    }

}
