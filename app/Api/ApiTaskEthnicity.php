<?php

namespace App\Api;

use App\Ethnicity;
use App\Task;
use Illuminate\Database\Eloquent\Model;

class ApiTaskEthnicity extends Model
{
    protected $table = 'task_ethnicity';
    protected $primaryKey = 'idtask_ethnicity';
    protected $appends = ['name'];
    protected $visible = ['idtask_ethnicity','name'];

    public function task(){
        return $this->belongsTo(Task::class,'idtask');
    }

    public function ethnicity(){
        return $this->belongsTo(Ethnicity::class,'idethnicity');
    }

    public function getNameAttribute()
    {
        return $this->ethnicity->name_en;
    }
}
