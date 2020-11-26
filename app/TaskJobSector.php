<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskJobSector extends Model
{
    protected $table = 'task_job_sector';
    protected $primaryKey = 'idtask_job_sector';

    public function task(){
        return $this->belongsTo(Task::class,'idtask');
    }
}
