<?php

namespace App\Http\Controllers;

use App\Canvassing;
use App\House;
use App\HouseDynamic;
use App\VotersCount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScreenController extends Controller
{
    public function pending(){
        $canvassings = Canvassing::where('idoffice',Auth::user()->idoffice)->where('status',2)->get();
        return view('screen.screen-pending', ['canvassings'=>$canvassings,'title' =>  __('Canvassing Screen')]);
    }
    public function pendingTable(){
        $canvassings = Canvassing::where('idoffice',Auth::user()->idoffice)->where('date','!=',date('Y-m-d'))->where('status',2)->orderBy('date','ASC')->get();
        foreach ($canvassings as $canvassing){
            $canvassing['canvassingType'] = $canvassing->type->name_en;
            $forecastingAttendance = $canvassing->attendanceForecasting()->where('status',1)->count();
            $canvassing['attendance'] = $forecastingAttendance;
            $villages = "";
            $housesTotal = 0;
            foreach ($canvassing->village as $village){
                $villages .= $village->village->name_en.'<br/>';
                $housesTotal +=  $village->village == null || $village->village->votes() == null ? 0 : $village->village->votes()->sum('houses');
            }
            $canvassing['villages'] = $villages;
            $canvassing['houses'] = $housesTotal;
            $canvassing['no'] = sprintf("%05d",$canvassing->canvassingNo);

        }
        return response()->json(['success' => $canvassings]);

    }

    public function today(){
        return view('screen.screen-today', ['title' =>  __('Canvassing Screen')]);
    }
    public function todayTable(){
        $canvassings = Canvassing::where('idoffice',Auth::user()->idoffice)->where('date',date('Y-m-d'))->where('status',2)->orderBy('time','ASC')->get();
        foreach ($canvassings as $canvassing){
            $canvassing['canvassingType'] = $canvassing->type->name_en;
            $forecastingAttendance = $canvassing->attendanceForecasting()->where('status',1)->count();
//            $actualAttendance = $canvassing->attendance()->where('status',1)->count();
//            $canvassing['attendance'] =  $actualAttendance.' / '.$forecastingAttendance;
            $canvassing['attendance'] = $forecastingAttendance;
            $villages = "";
            $houses = 0;
            foreach ($canvassing->village as $village){
                $villages .= $village->village->name_en.'<br/>';
                $houses +=  $village->village == null || $village->village->votes() == null ? 0 : $village->village->votes()->sum('houses');
            }
            $canvassing['villages'] = $villages;
            $canvassing['houses'] = $houses;
            $canvassing['no'] = sprintf("%05d",$canvassing->canvassingNo);

        }
        return response()->json(['success' => $canvassings]);

    }

    public function ongoing(){
        return view('screen.screen-ongoing', ['title' =>  __('Canvassing Screen')]);

    }
    public function ongoingTable(){
        $canvassings = Canvassing::where('idoffice',Auth::user()->idoffice)->where('status',1)->orderBy('canvassingNo','ASC')->get();
        $collection = collect();
        foreach ($canvassings as $canvassing){
            $totalAttendance = 0;
            $totalForecasting = 0;
            $villagesNames = "";
            $housesTotal = 0;
            $totalVoters = 0;

            foreach ($canvassing->village as $village){
                $totalForecasting += $canvassing->attendanceForecasting()->where('idvillage',$village->idvillage)->where('status',1)->count();
                $totalAttendance += $canvassing->attendance()->where('status',1)->where('idvillage',$village->idvillage)->count();
                $villagesNames .= $village->village->name_en.'<br/>';
                $housesTotal +=  $village->village == null || $village->village->votes() == null ? 0 : $village->village->votes()->sum('houses');
                $forecastingTotal = VotersCount::where('idvillage',$village->idvillage)->where('status',2)->first();
                if($forecastingTotal != null){
                    $totalVoters +=  intval($forecastingTotal->total);
                }
                else{
                    $totalVoters +=  $village->village == null || $village->village->votes() == null ? 0 : $village->village->votes()->sum('total');

                }

            }
            $elders = HouseDynamic::where('idcanvassing',$canvassing->idcanvassing)->where('idhouse_member',1)->where('status',1)->get();
            $eldersFavorable = $elders->where('idvoting_condition',1)->sum('count');
            $eldersTotal = $elders->sum('count');

            $young = HouseDynamic::where('idcanvassing',$canvassing->idcanvassing)->where('idhouse_member',2)->where('status',1)->get();
            $youngFavorable = $young->where('idvoting_condition',1)->sum('count');
            $youngTotal = $young->sum('count');

            $first = HouseDynamic::where('idcanvassing',$canvassing->idcanvassing)->where('idhouse_member',3)->where('status',1)->get();
            $firstFavorable = $first->where('idvoting_condition',1)->sum('count');
            $firstTotal = $first->sum('count');

            $samurdhi  = HouseDynamic::whereHas('house',function ($q){
                $q->where('idhouse_condition',1);
            })->where('idcanvassing',$canvassing->idcanvassing)->where('status',1)->get();
            $samurdhiFavorable = $samurdhi->where('idvoting_condition',1)->sum('count');
            $samurdhiTotal = $samurdhi->sum('count');

            $low  = HouseDynamic::whereHas('house',function ($q){
                $q->where('idhouse_condition',2);
            })->where('idcanvassing',$canvassing->idcanvassing)->where('status',1)->get();
            $lowFavorable = $low->where('idvoting_condition',1)->sum('count');
            $lowTotal = $low->sum('count');

            $middle  = HouseDynamic::whereHas('house',function ($q){
                $q->where('idhouse_condition',3);
            })->where('idcanvassing',$canvassing->idcanvassing)->where('status',1)->get();
            $middleFavorable = $middle->where('idvoting_condition',1)->sum('count');
            $middleTotal = $middle->sum('count');

            $luxury  = HouseDynamic::whereHas('house',function ($q){
                $q->where('idhouse_condition',4);
            })->where('idcanvassing',$canvassing->idcanvassing)->where('status',1)->get();
            $luxuryFavorable = $luxury->where('idvoting_condition',1)->sum('count');
            $luxuryTotal = $luxury->sum('count');

            $superLuxury  = HouseDynamic::whereHas('house',function ($q){
                $q->where('idhouse_condition',5);
            })->where('idcanvassing',$canvassing->idcanvassing)->where('status',1)->get();
            $superLuxuryFavorable = $superLuxury->where('idvoting_condition',1)->sum('count');
            $superLuxuryTotal = $superLuxury->sum('count');

            $housesVisited = House::whereHas('houseDynamics',function ($q) use($canvassing){
                $q->where('idcanvassing',$canvassing->idcanvassing);
            })->where('status',1)->count();
            $totalFavorable = HouseDynamic::where('idcanvassing',$canvassing->idcanvassing)->where('idvoting_condition',1)->where('status',1)->sum('count');

            $response['canvassingType'] = $canvassing->type->name_en;
            $response['location_en'] = $canvassing->location_en;
            $response['attendance'] =  $totalAttendance.' / '.$totalForecasting;
            $response['name_en'] = $canvassing->name_en;
            $response['time'] = $canvassing->time;
            $response['published_at'] = $canvassing->published_at;
            $response['no'] = sprintf("%05d",$canvassing->canvassingNo);
            $response['villages'] = $villagesNames;
            $response['started'] = Carbon::parse($canvassing->started_at)->format('Y-m-d');
            $response['houses'] = $housesVisited.' / '.$housesTotal;
            $response['elders'] = $eldersFavorable.' / '.$eldersTotal;
            $response['young'] = $youngFavorable.' / '.$youngTotal;
            $response['first'] = $firstFavorable.' / '.$firstTotal;
            $response['totalVoters'] = $totalFavorable.' / '.$totalVoters;
            $response['samurdhi'] = $samurdhiFavorable.' / '.$samurdhiTotal;
            $response['low'] = $lowFavorable.' / '.$lowTotal;
            $response['middle'] = $middleFavorable.' / '.$middleTotal;
            $response['luxury'] = $luxuryFavorable.' / '.$luxuryTotal;
            $response['super'] = $superLuxuryFavorable.' / '.$superLuxuryTotal;
            $response['idcanvassing'] = $canvassing->idcanvassing;
            $collection->push($response);

        }
        return response()->json(['success' => $collection]);

    }

    public function routeOnMap(Request $request){
        return view('screen.map-route')->with(['title'=>'View Canvassing Route','canvassingId'=>$request['reference']]);
    }
}
