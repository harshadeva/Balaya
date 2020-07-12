<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskBranchSociety extends Model
{
    protected $table = 'task_branch_society';
    protected $primaryKey = 'idtask_branch_society';

    public function task(){
        return $this->hasMany(Task::class,'idtask_type');
    }

    public function position(){
        return $this->belongsTo(Position::class,'idposition');
    }
}
