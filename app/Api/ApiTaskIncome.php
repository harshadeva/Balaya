<?php

namespace App\Api;

use App\NatureOfIncome;
use App\Task;
use Illuminate\Database\Eloquent\Model;

class ApiTaskIncome extends Model
{
    protected $table = 'task_income';
    protected $primaryKey = 'idtask_income';
    protected $appends = ['name'];
    protected $visible = ['idtask_income','name'];

    public function task(){
        return $this->belongsTo(Task::class,'idtask');
    }

    public function income(){
        return $this->belongsTo(NatureOfIncome::class,'idnature_of_income');
    }

    public function getNameAttribute()
    {
        return $this->income->name_en;
    }
}
