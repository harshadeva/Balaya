<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    protected $table = 'post';
    protected $primaryKey = 'idPost';

    public function user(){
        return $this->belongsTo(User::class,'idUser');
    }

    public function office(){
        return $this->belongsTo(Office::class,'idoffice');
    }

    public function attachments(){
        return $this->hasMany(PostAttachment::class,'idPost');
    }

    public function responsePanel(){
        return $this->hasMany(ResponsePanel::class,'idPost');
    }

    public function apiAttachments(){
        return $this->hasMany(Api\ApiPostAttachment::class,'idPost');
    }

    public function responses(){
        return $this->hasMany(PostResponse::class,'idPost');
    }

    public function beneficialDistrict(){
        return $this->hasMany(BeneficialDistrict::class,'idPost');
    }

    public function beneficialElectionDivision(){
        return $this->hasMany(BeneficialElectionDivision::class,'idPost');
    }

    public function beneficialPollingBooth(){
        return $this->hasMany(BeneficialPollingBooth::class,'idPost');
    }


    public function beneficialGramasewaDivision(){
        return $this->hasMany(BeneficialGramasewaDivision::class,'idPost');
    }

    public function beneficialVillage(){
        return $this->hasMany(BeneficialVillage::class,'idPost');
    }

    public function beneficialCategory(){
        return $this->hasMany(BeneficialCat::class,'idPost');
    }

    public function getSize(){
        $attachment =  $this->attachments()->sum('size');
        $responses = $this->responses()->sum('size');
        return $attachment + $responses;
    }

    public function nextPostNo($office){
        $last = $this->whereHas('user', function (Builder $query) use($office) {
            $query->where('idoffice',$office);
        })->latest('idPost')->first();
        return  $last == null ? 1 : $last->post_no + 1;
    }


    public function getCommentsCountAttribute() {
        return  $this->responses()->count();
    }

    public function userCommentsCount($userId){
        return  $this->responses()->where('idUser',$userId)->count();
    }


    //Location relationships

    public function postVillages(){
        return $this->hasMany(PostVillage::class,'idPost');
    }

    public function postGramasewaDivision()
    {
        return $this->hasMany(PostGramasewaDivision::class, 'idPost');
    }

    public function postPollingBooths(){
        return $this->hasMany(PostPollingBooth::class,'idPost');
    }

    public function postElectionDivisions(){
        return $this->hasMany(PostElectionDivision::class,'idPost');
    }

    public function postDistrict(){
        return $this->hasMany(PostDistrict::class,'idPost');
    }

    //Location relationships end

    //Community relationships

    public function postEthnicities(){
        return $this->hasMany(PostEthnicity::class,'idPost');
    }

    public function postReligions(){
        return $this->hasMany(PostReligion::class,'idPost');
    }

    public function postEducations(){
        return $this->hasMany(PostEducation::class,'idPost');
    }

    public function postIncomes(){
        return $this->hasMany(PostIncome::class,'idPost');
    }

    public function postCareers(){
        return $this->hasMany(PostCareer::class,'idPost');
    }
    //Community relationships end
}
