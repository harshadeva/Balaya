<?php

namespace App\Api;

use App\EducationalQualification;
use App\Task;
use Illuminate\Database\Eloquent\Model;

class ApiTaskEducation extends Model
{
    protected $table = 'task_education';
    protected $primaryKey = 'idtask_education';
    protected $appends = ['name'];
    protected $visible = ['idtask_education','name'];

    public function task(){
        return $this->belongsTo(Task::class,'idtask');
    }

    public function education(){
        return $this->belongsTo(EducationalQualification::class,'ideducational_qualification');
    }

    public function getNameAttribute()
    {
        return $this->education->name_en;
    }
}
