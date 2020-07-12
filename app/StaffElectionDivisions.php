<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffElectionDivisions extends Model
{
    protected $table = 'staff_election_devision';
    protected $primaryKey = 'idstaff_election_devision';

    public function mediaStaff(){
        return $this->belongsTo(User::class,'idmedia_staff');
    }

    public function electionDivision(){
        return $this->belongsTo(ElectionDivision::class,'idelection_division');
    }
}
