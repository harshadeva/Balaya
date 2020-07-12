<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    protected $table = 'usermaster';
    protected $primaryKey = 'idUser';
    protected $appends = ['age'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fName', 'email', 'password', 'iduser_role', 'idbranch'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function isActive(){
        if($this->iduser_role > 2) {
            if ($this->status != 1 || $this->office->status != 1) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * return user role meta data
     */
    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'iduser_role');
    }

    public function getType(){
        if($this->iduser_role  == 6){
            return $this->agent();
        }
        else if($this->iduser_role  == 7){
            return $this->member();
        }
        else if($this->iduser_role  == 4){
            return false;
        }
    }

    public function authAccessToken(){
        return $this->hasMany(OauthAccessToken::class,'user_id');
    }

    public function getAgeAttribute() {
        $d1 = new Carbon(date('Y-m-d', strtotime($this->bday)));
        $d2 = new Carbon(date('Y-m-d', strtotime(date('y-m-d'))));

        $diff = $d2->diff($d1);
        return intval($diff->y);

    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'idoffice');
    }

    public function userTitle()
    {
        return $this->belongsTo(UserTitle::class, 'iduser_title');
    }

    public function taskAgent()
    {
        return $this->hasMany(Task::class, 'idUser');
    }

    public function responsePanel()
    {
        return $this->hasMany(ResponsePanel::class, 'idUser');
    }

    public function taskAssigned()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }

    public function post()
    {
        return $this->hasMany(Post::class, 'idUser');
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'idUser');
    }

    public function agent()
    {
        return $this->hasOne(Agent::class, 'idUser');
    }

    public function officeAdmin()
    {
        return $this->hasOne(OfficeAdmin::class, 'idUser');
    }

    public function officeStaff()
    {
        return $this->hasOne(OfficeStaff::class, 'idUser');
    }

    public function responses()
    {
        return $this->hasMany(PostResponse::class, 'idUser');
    }

    public function messageToUsers()
    {
        return $this->hasMany(DirectMessage::class, 'to_idUser');
    }

    public function messageFromUsers()
    {
        return $this->hasMany(DirectMessage::class, 'from_idUser');
    }

    public function userSociety()
    {
        return $this->hasMany(UserSociety::class, 'idUser');
    }

    public function sms(){
        return $this->hasMany(Sms::class,'idUser');
    }

    public function assignedElectionDivisions(){
        if($this->iduser_role == 8){
            return $this->hasMany(StaffElectionDivisions::class,'idmedia_staff');
        }
        else{
            return 0;
        }
    }

    public function mapLocations(){
        return $this->hasMany(AgentMapLocation::class,'idUser');
    }
}
