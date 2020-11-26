<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PostResponse extends Model
{
    protected $table = 'post_response';
    protected $primaryKey = 'idpost_response';
    protected $appends = array('full_path');

    public function post()
    {
        return $this->belongsTo(Post::class, 'idPost');
    }

    public function analysis()
    {
        return $this->hasMany(Analysis::class, 'referrence_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    public function getFullPathAttribute()
    {
        if ($this->response_type == 2) {
            return 'storage/' . $this->post->user->office->random . '/comments/images/' . $this->attachment;
        } else if ($this->response_type == 3) {
            return 'storage/' . $this->post->user->office->random . '/comments/videos/' . $this->attachment;
        } else if ($this->response_type == 4) {
            return 'storage/' . $this->post->user->office->random . '/comments/audios/' . $this->attachment;
        } else {
            return '';
        }
    }

    public function getPath()
    {
        if ($this->response_type == 2) {
            return 'storage/' . $this->post->user->office->random . '/comments/images/' . $this->attachment;
        } else if ($this->response_type == 3) {
            return 'storage/' . $this->post->user->office->random . '/comments/videos/' . $this->attachment;
        } else if ($this->response_type == 4) {
            return 'storage/' . $this->post->user->office->random . '/comments/audios/' . $this->attachment;
        } else {
            return '';
        }
    }

    public function getResponseTextAttribute()
    {
        if ($this->response_type == 1) {
            return 'TEXT';
        }else if ($this->response_type == 2) {
            return 'IMAGE';
        } else if ($this->response_type == 3) {
            return 'VIDEO';
        } else if ($this->response_type == 4) {
            return 'AUDIO';
        } else {
            return 'UNKNOWN';
        }
    }

    public static  function TOTAL_COUNT($post,$start,$end,$village,$gramasewa,$polling,$election){
       $query = PostResponse::query();
        if (!empty($start) && !empty($end)) {
            $startDate = date('Y-m-d', strtotime($start));
            $endDate = date('Y-m-d', strtotime($end . ' +1 day' ));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->where('idpost',$post)->where('status',1)->whereHas('user',function ($q) use ($village,$gramasewa,$polling,$election){
            $q->whereHas('agent',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                    $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                    $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                    $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                    $q->where('idelection_division',$election);

                }
                else{
                    $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            })->orWhereHas('member',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                    $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                    $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                    $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                    $q->where('idelection_division',$election);

                }
                else{
                    $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            });
        })->count();
    }


    public static  function ETHNICITY_COUNT($post,$count,$start,$end,$village,$gramasewa,$polling,$election){
        $id = 'idethnicity';

        $query = PostResponse::query();
        if (!empty($start) && !empty($end)) {
            $startDate = date('Y-m-d', strtotime($start));
            $endDate = date('Y-m-d', strtotime($end . ' +1 day' ));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $responses = $query->where('idpost',$post)->where('status',1)->whereHas('user',function ($q) use ($village,$gramasewa,$polling,$election){
            $q->whereHas('agent',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                     $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                     $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                     $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                     $q->where('idelection_division',$election);

                }
                else{
                     $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            })->orWhereHas('member',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                     $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                     $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                     $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                     $q->where('idelection_division',$election);

                }
                else{
                     $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            });
        })->get();
        $grouped  = $responses->groupBy(function ($item, $key) use ($id){
            return  $item->user->getType->$id;
        });
        $string = "";
        $collection = collect();

        foreach ($grouped as $key=>$value){
                $name = Ethnicity::find($key)->name_en;
                $numberOfRecords = count($value);
                $collection->push(['name'=>$name,'count'=>$numberOfRecords]);
        }

        $collection = $collection->SortByDesc('count')->take($count);
        foreach ($collection as $item){

          $string .=  ucfirst(strtolower($item['name']))." : ". $item['count']."<br/>";
        }
        return $string != null ? $string : 0;
    }

    public static  function RELIGION_COUNT($post,$count,$start,$end,$village,$gramasewa,$polling,$election){
        $id = 'idreligion';

        $query = PostResponse::query();
        if (!empty($start) && !empty($end)) {
            $startDate = date('Y-m-d', strtotime($start));
            $endDate = date('Y-m-d', strtotime($end . ' +1 day' ));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $responses = $query->where('idpost',$post)->where('status',1)->whereHas('user',function ($q) use ($village,$gramasewa,$polling,$election){
            $q->whereHas('agent',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                    $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                    $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                    $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                    $q->where('idelection_division',$election);

                }
                else{
                    $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            })->orWhereHas('member',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                    $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                    $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                    $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                    $q->where('idelection_division',$election);

                }
                else{
                    $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            });
        })->get();
        $grouped  = $responses->groupBy(function ($item, $key) use ($id){
            return  $item->user->getType->$id;
        });
        $string = "";
        $collection = collect();

        foreach ($grouped as $key=>$value){
            $name = Religion::find($key)->name_en;
            $numberOfRecords = count($value);
            $collection->push(['name'=>$name,'count'=>$numberOfRecords]);
        }

        $collection = $collection->SortByDesc('count')->take($count);
        foreach ($collection as $item){

            $string .=  ucfirst(strtolower($item['name']))." : ". $item['count']."<br/>";
        }
        return $string != null ? $string : 0;
    }

    public static  function CAREER_COUNT($post,$count,$start,$end,$village,$gramasewa,$polling,$election){
        $id = 'idcareer';

        $query = PostResponse::query();
        if (!empty($start) && !empty($end)) {
            $startDate = date('Y-m-d', strtotime($start));
            $endDate = date('Y-m-d', strtotime($end . ' +1 day' ));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $responses = $query->where('idpost',$post)->where('status',1)->whereHas('user',function ($q) use ($village,$gramasewa,$polling,$election){
            $q->whereHas('agent',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                    $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                    $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                    $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                    $q->where('idelection_division',$election);

                }
                else{
                    $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            })->orWhereHas('member',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                    $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                    $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                    $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                    $q->where('idelection_division',$election);

                }
                else{
                    $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            });
        })->get();
        $grouped  = $responses->groupBy(function ($item, $key) use ($id){
            return  $item->user->getType->$id;
        });
        $string = "";
        $collection = collect();

        foreach ($grouped as $key=>$value){
            $name = Career::find($key)->name_en;
            $numberOfRecords = count($value);
            $collection->push(['name'=>$name,'count'=>$numberOfRecords]);
        }

        $collection = $collection->SortByDesc('count')->take($count);
        foreach ($collection as $item){

            $string .=  ucfirst(strtolower($item['name']))." : ". $item['count']."<br/>";
        }
        return $string != null ? $string : 0;
    }

    public static  function EDUCATION_COUNT($post,$count,$start,$end,$village,$gramasewa,$polling,$election){
        $id = 'ideducational_qualification';

        $query = PostResponse::query();
        if (!empty($start) && !empty($end)) {
            $startDate = date('Y-m-d', strtotime($start));
            $endDate = date('Y-m-d', strtotime($end . ' +1 day' ));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $responses = $query->where('idpost',$post)->where('status',1)->whereHas('user',function ($q) use ($village,$gramasewa,$polling,$election){
            $q->whereHas('agent',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                    $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                    $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                    $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                    $q->where('idelection_division',$election);

                }
                else{
                    $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            })->orWhereHas('member',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                    $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                    $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                    $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                    $q->where('idelection_division',$election);

                }
                else{
                    $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            });
        })->get();
        $grouped  = $responses->groupBy(function ($item, $key) use ($id){
            return  $item->user->getType->$id;
        });
        $string = "";
        $collection = collect();

        foreach ($grouped as $key=>$value){
            $name = EducationalQualification::find($key)->name_en;
            $numberOfRecords = count($value);
            $collection->push(['name'=>$name,'count'=>$numberOfRecords]);
        }

        $collection = $collection->SortByDesc('count')->take($count);
        foreach ($collection as $item){

            $string .=  ucfirst(strtolower($item['name']))." : ". $item['count']."<br/>";
        }
        return $string != null ? $string : 0;
    }

    public static  function INCOME_COUNT($post,$count,$start,$end,$village,$gramasewa,$polling,$election){
        $id = 'idnature_of_income';

        $query = PostResponse::query();
        if (!empty($start) && !empty($end)) {
            $startDate = date('Y-m-d', strtotime($start));
            $endDate = date('Y-m-d', strtotime($end . ' +1 day' ));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $responses = $query->where('idpost',$post)->where('status',1)->whereHas('user',function ($q) use ($village,$gramasewa,$polling,$election){
            $q->whereHas('agent',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                    $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                    $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                    $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                    $q->where('idelection_division',$election);

                }
                else{
                    $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            })->orWhereHas('member',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                    $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                    $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                    $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                    $q->where('idelection_division',$election);

                }
                else{
                    $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            });
        })->get();
        $grouped  = $responses->groupBy(function ($item, $key) use ($id){
            return  $item->user->getType->$id;
        });
        $string = "";
        $collection = collect();

        foreach ($grouped as $key=>$value){
            $name = NatureOfIncome::find($key)->name_en;
            $numberOfRecords = count($value);
            $collection->push(['name'=>$name,'count'=>$numberOfRecords]);
        }

        $collection = $collection->SortByDesc('count')->take($count);
        foreach ($collection as $item){

            $string .=  ucfirst(strtolower($item['name']))." : ". $item['count']."<br/>";
        }
        return $string != null ? $string : 0;
    }

    public static  function JOB_SECTOR_COUNT($post,$count,$start,$end,$village,$gramasewa,$polling,$election){
        $id = 'is_government';

        $query = PostResponse::query();
        if (!empty($start) && !empty($end)) {
            $startDate = date('Y-m-d', strtotime($start));
            $endDate = date('Y-m-d', strtotime($end . ' +1 day' ));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $responses = $query->where('idpost',$post)->where('status',1)->whereHas('user',function ($q) use ($village,$gramasewa,$polling,$election){
            $q->whereHas('agent',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                    $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                    $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                    $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                    $q->where('idelection_division',$election);

                }
                else{
                    $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            })->orWhereHas('member',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                    $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                    $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                    $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                    $q->where('idelection_division',$election);

                }
                else{
                    $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            });
        })->get();
        $grouped  = $responses->groupBy(function ($item, $key) use ($id){
            return  $item->user->getType->$id;
        });
        $string = "";
        $collection = collect();

        foreach ($grouped as $key=>$value){
            if($key == 1){
                $name = 'Government';
            }
            else if($key == 2){
                $name = 'Private';
            }
            else if($key == 3){
                $name = 'Non-Government';
            }
            else if($key == 4){
                $name = 'Undisclosed';
            }
            else{
                $name = '';
            }

            $numberOfRecords = count($value);
            $collection->push(['name'=>$name,'count'=>$numberOfRecords]);
        }

        $collection = $collection->SortByDesc('count')->take($count);
        foreach ($collection as $item){

            $string .=  ucfirst(strtolower($item['name']))." : ". $item['count']."<br/>";
        }
        return $string != null ? $string : 0;
    }

    public static  function GENDER($post,$count,$start,$end,$village,$gramasewa,$polling,$election){
        $id = 'is_government';

        $query = PostResponse::query();
        if (!empty($start) && !empty($end)) {
            $startDate = date('Y-m-d', strtotime($start));
            $endDate = date('Y-m-d', strtotime($end . ' +1 day' ));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $responses = $query->where('idpost',$post)->where('status',1)->whereHas('user',function ($q) use ($village,$gramasewa,$polling,$election){
            $q->whereHas('agent',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                    $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                    $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                    $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                    $q->where('idelection_division',$election);

                }
                else{
                    $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            })->orWhereHas('member',function ($q) use ($village,$gramasewa,$polling,$election){
                if($village != null){
                    $q->where('idvillage',$village);
                }
                else if($gramasewa != null){
                    $q->where('idgramasewa_division',$gramasewa);

                }
                else if($polling != null){
                    $q->where('idpolling_booth',$polling);

                }
                else if($election != null){
                    $q->where('idelection_division',$election);

                }
                else{
                    $q->where('iddistrict',Auth::user()->office->iddistrict);
                }
            });
        })->get();
        $grouped  = $responses->groupBy(function ($item, $key) use ($id){
            return  $item->user->getType->$id;
        });
        $string = "";
        $collection = collect();

        foreach ($grouped as $key=>$value){
            if($key == 1){
                $name = 'Male';
            }
            else if($key == 2){
                $name = 'Female';
            }
            else if($key == 3){
                $name = 'Other';
            }
            else if($key == 4){
                $name = 'Undisclosed';
            }
            else{
                $name = '';
            }

            $numberOfRecords = count($value);
            $collection->push(['name'=>$name,'count'=>$numberOfRecords]);
        }

        $collection = $collection->SortByDesc('count')->take($count);
        foreach ($collection as $item){

            $string .=  ucfirst(strtolower($item['name']))." : ". $item['count']."<br/>";
        }
        return $string != null ? $string : 0;
    }

}
