<?php

namespace App\Api;

use App\Task;
use Illuminate\Database\Eloquent\Model;

class ApiTaskAge extends Model
{
    protected $table = 'task_age';
    protected $primaryKey = 'idtask_age';
    protected $appends = ['name'];
    protected $visible = ['task_age','comparison','minAge','maxAge'];

    public function task(){
        return $this->hasOne(Task::class,'idtask');
    }

}
