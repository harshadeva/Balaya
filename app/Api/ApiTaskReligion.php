<?php

namespace App\Api;

use App\Religion;
use App\Task;
use Illuminate\Database\Eloquent\Model;

class ApiTaskReligion extends Model
{
    protected $table = 'task_religion';
    protected $primaryKey = 'idtask_religion';
    protected $appends = ['name'];
    protected $visible = ['idtask_religion','name'];

    public function task(){
        return $this->belongsTo(Task::class,'idtask');
    }

    public function religion(){
        return $this->belongsTo(Religion::class,'idreligion');
    }

    public function getNameAttribute()
    {
        return $this->religion->name_en;
    }
}
