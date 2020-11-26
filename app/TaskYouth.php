<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskYouth extends Model
{
    protected $table = 'task_youth_society';
    protected $primaryKey = 'idtask_youth_society';

    public function task(){
        return $this->hasMany(Task::class,'idtask_type');
    }

    public function position(){
        return $this->belongsTo(Position::class,'idposition');
    }
}
