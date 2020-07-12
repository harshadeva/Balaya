<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskEthnicity extends Model
{
    protected $table = 'task_ethnicity';
    protected $primaryKey = 'idtask_ethnicity';

    public function task(){
        return $this->belongsTo(Task::class,'idtask');
    }

    public function ethnicity(){
        return $this->belongsTo(Ethnicity::class,'idethnicity');
    }

}
