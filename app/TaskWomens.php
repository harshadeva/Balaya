<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskWomens extends Model
{
    protected $table = 'task_women_society';
    protected $primaryKey = 'task_women_society';

    public function task(){
        return $this->hasMany(Task::class,'idtask_type');
    }

    public function position(){
        return $this->belongsTo(Position::class,'idposition');
    }
}
