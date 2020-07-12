<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskIncome extends Model
{
    protected $table = 'task_income';
    protected $primaryKey = 'idtask_income';

    public function task(){
        return $this->belongsTo(Task::class,'idtask');
    }

    public function income(){
        return $this->belongsTo(NatureOfIncome::class,'idnature_of_income');
    }
}
