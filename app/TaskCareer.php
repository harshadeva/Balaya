<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskCareer extends Model
{
    protected $table = 'task_career';
    protected $primaryKey = 'idtask_career';

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
