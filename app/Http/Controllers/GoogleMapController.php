<?php

namespace App\Http\Controllers;

use App\Agent;
use App\AgentMapLocation;
use App\Canvassing;
use App\House;
use App\User;
use App\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleMapController extends Controller
{
    public function agentDistribution(Request $request){
        $district = Auth::user()->office->iddistrict;
        $villages = Village::where('iddistrict',$district)->get();
        return view('maps.agent_distribution')->with(['title'=>'Agent Distribution','villages'=>$villages]);
    }

    public function getVillageLocations(){
        $district = Auth::user()->office->iddistrict;
        $villages = Village::where('iddistrict',$district)->whereHas('votes',function ($q){
            $q->whereNotNull('lat')->whereNotNull('long')->where('status',1);
        })->where('status',1)->get();
        foreach ($villages as $key=>$village) {

           $village['lat'] = $village->votes->lat;
           $village['long'] = $village->votes->long;
           $village['voters'] = $village->getFavourableVotersCount(). ' - ' . $village->getTotalVotersCount();
           $village['elder'] = $village->getFavourableElderCount();
           $village['young'] = $village->getFavourableYoungCount();
           $village['first'] = $village->getFavourableFirstCount();

            $villageAgent = User::where('idoffice',Auth::user()->idoffice)->whereHas('agent',function($q) use ($village){
                    $q->where('idvillage',$village->idvillage)->where('status',1);
                })->where('status',1)->first();
                if($villageAgent != null) {
                    $village['name'] = $villageAgent->fName . ' ' .$villageAgent->lName;
                    $village['contact'] = $villageAgent->contact_no1;
                }
                else{
                    $village['name'] = '';
                    $village['contact'] = '';
                }
                $agent = Agent::where('idvillage', $village->idvillage)->whereHas('user', function ($q) {
                    $q->where('idoffice', Auth::user()->idoffice);
                })->first();
                if ($agent != null) {
                    $village['agent'] = $agent->user->fName;
                } else {
                    $village['agent'] = '';
                }
        }
        return response()->json(['success' => $villages]);
    }

    public function canvassingPath(Request $request){
        return view('maps.canvassing_path')->with(['title'=>'Canvassing Path']);
    }

    public function getScreenMapData(Request $request){
        $canvassing = Canvassing::find($request['id']);
        $pathCoordinates = AgentMapLocation::where('idcanvassing',$canvassing->idcanvassing)->where('status',1)->get();
        foreach ($pathCoordinates as $coordinate){
            $coordinate['name'] = $coordinate->user->fName;
            $coordinate['lng'] = floatval($coordinate->long);
            $coordinate['lat'] = floatval($coordinate->lat);
            $coordinate['contact'] = $coordinate->user->contact_no1;
            $coordinate['time'] = $coordinate->created_at->format('H:i:s A');
        }
        $pathCoordinates = $pathCoordinates->groupBy('name');
        $houses = House::where('idoffice',Auth::user()->idoffice)->where('status',1)->get();
        return response()->json(['success' => ['path'=>$pathCoordinates,'houses'=>$houses]]);
    }

    public function liveMap(Request $request){
        return view('maps.live_map')->with(['title'=>'Live Map']);

    }

    public function routeTest(Request $request){
        $re = $request['canvassingId'];
        return view('test.direction_route_test', ['title' =>  __('Test'),'canvassingId'=>$re]);

    }

    public function heatMap(Request $request){
        return view('maps.heat_map', ['title' =>  __('Heat map')]);
    }
}
