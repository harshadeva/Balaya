<?php

namespace App\Api;

use App\Career;
use App\Task;
use Illuminate\Database\Eloquent\Model;

class ApiTaskCareer extends Model
{
    protected $table = 'task_career';
    protected $primaryKey = 'idtask_career';
    protected $appends = ['name'];
    protected $visible = ['idtask_career','name'];


    public function task(){
        return $this->belongsTo(Task::class,'idtask');
    }

    public function career(){
        return $this->belongsTo(Career::class,'idcareer');
    }

    public function getNameAttribute()
    {
        return $this->career->name_en;
    }
}
