<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskEducation extends Model
{
    protected $table = 'task_education';
    protected $primaryKey = 'idtask_education';

    public function task(){
        return $this->belongsTo(Task::class,'idtask');
    }

    public function education(){
        return $this->belongsTo(EducationalQualification::class,'ideducational_qualification');
    }
}
