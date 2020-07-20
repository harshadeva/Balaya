<?php

namespace App\Http\Controllers;

use App\Canvassing;
use App\CanvassingType;
use App\CanvassingVillage;
use App\CanvassingVillageTemp;
use App\ElectionDivision;
use App\House;
use App\HouseDynamic;
use App\Http\Controllers\Api\ApiNotificationController;
use App\User;
use App\Village;
use App\VotersCount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanvassingController extends Controller
{
    public function create(){
        $electionDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status',1)->get();
        $canvassingTypes = CanvassingType::where('status',1)->latest()->get();
        return view('canvassing.create', ['title' =>  __('Create Canvassing'),'electionDivisions'=>$electionDivisions,'canvassingTypes'=>$canvassingTypes]);

    }

    public function addVillageTemporary(Request $request){
        $validationMessages = [
            'village.required' => 'Village should be provided!'
        ];

        $validator = \Validator::make($request->all(), [
            'village' => 'required'
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $village = CanvassingVillageTemp::where('idUser',Auth::user()->idUser)->where('idvillage',$request['village'])->first();
        if($village == null) {

            $validateUser = User::where('idoffice',Auth::user()->idoffice)->whereHas('agent',function ($q) use ($request){
                $q->where('idvillage',$request['village']);
            })->where('status',1)->count();

            if($validateUser == 0  ){
                return response()->json(['errors' => ['error'=>'`'. ucfirst(strtolower(Village::find(intval($request['village']))->name_en)).'` Village Agent Invalid.']]);
            }
            if($validateUser > 1){
                return response()->json(['errors' => ['error'=>'`'. ucfirst(strtolower(Village::find(intval($request['village']))->name_en)).'` Village has two active agents.Please deactivate one.']]);
            }
            if(VotersCount::where('idvillage',$request['village'])->first() == null){
                return response()->json(['errors' => ['error'=>'Agent not submitted voters forecasting yet for `'. ucfirst(strtolower(Village::find(intval($request['village']))->name_en)).'` village.']]);
            }

            $village = new CanvassingVillageTemp();
            $village->idUser = Auth::user()->idUser;
            $village->idvillage = $request['village'];
            $village->status = 1;
            $village->save();
        }

        return response()->json(['success' => 'Village Added']);

    }

    public function loadVillageTemp(Request $request){

        $villages = CanvassingVillageTemp::with(['village'])->where('idUser',Auth::user()->idUser)->get();
        foreach ($villages as $village){
            $agent = User::where('idoffice',Auth::user()->idoffice)->whereHas('agent',function ($q) use($village){
                $q->where('idvillage',$village->idvillage);
            })->first();
            $village['name'] = strtoupper($village->village->name_en);
            $votersCount = VotersCount::where('idvillage',$village->idvillage)->where('idoffice',Auth::user()->idoffice)->first();

            if($votersCount != null){
                $village['houses'] = $votersCount->houses;
            }
            else{
                $village['houses'] = 0;
            }
            if($agent != null){
                $village['agent'] = strtoupper($agent->fName.' '.$agent->lName);
            }
            else{
                $village['agent'] = 'NO AGENT';
            }
        }
        return $villages;
    }

    public function deleteVillageTemp(Request $request){
        $validationMessages = [
            'id.required' => 'Id should be provided!'
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required'
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $villages = CanvassingVillageTemp::find(intval($request['id']));
        $villages->delete();
    }

    public function saveCanvassing(Request $request){

        $validationMessages = [
            'canvassingType.required' => 'Canvassing type should be provided!',
            'location_en.required' => 'Location in english should be provided!',
            'date.required' => 'Date should be provided!',
            'time.required' => 'Time should be provided!'
        ];

        $validator = \Validator::make($request->all(), [
            'canvassingType' => 'required',
            'location_en' => 'required',
            'date' => 'required',
            'time' => 'required',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $tempVillages = CanvassingVillageTemp::where('idUser',Auth::user()->idUser)->get();
        if($tempVillages->count() > 0) {
            foreach ($tempVillages as $tempVillage) {
                $validateUser = User::where('idoffice', Auth::user()->idoffice)->whereHas('agent', function ($q) use ($tempVillage) {
                    $q->where('idvillage', $tempVillage->idvillage);
                })->where('status', 1)->count();

                if ($validateUser == 0 || $validateUser > 1) {
                    return response()->json(['errors' => ['error' => '`' . ucfirst(strtolower($tempVillage->village->name_en)) . '` Village Agent Invalid.']]);
                }
            }
        }
        else{

            return response()->json(['errors' => ['error' => 'Please assign at lease one village.']]);
        }
        $electionDivision = $tempVillages[0]->village->idelection_division;
        $pollingBooth = $tempVillages[0]->village->idpolling_booth;

        $canvassing = new Canvassing();
        $canvassing->idUser = Auth::user()->idUser;
        $canvassing->idoffice = Auth::user()->idoffice;
        $canvassing->canvassingNo = $canvassing->nextCanvassingNo(Auth::user()->idoffice);
        $canvassing->idelection_division =  $electionDivision;
        $canvassing->idpolling_booth = $pollingBooth;
        $canvassing->idcanvassing_type = $request->canvassingType;
        $canvassing->name_en = $request->name_en;
        $canvassing->name_si = $request->name_si;
        $canvassing->name_ta = $request->name_ta;
        $canvassing->location_en = $request->location_en;
        $canvassing->location_si = $request->location_si;
        $canvassing->location_ta = $request->location_ta;
        $canvassing->description_en = $request->description_en;
        $canvassing->description_si = $request->description_si;
        $canvassing->description_ta = $request->description_ta;
        $canvassing->date = date('Y-m-d', strtotime($request->date));
        $canvassing->time = date('H:i:s', strtotime($request->time));
        $canvassing->formInDetail = $request['isDetail'] == 'on' ? 1 : 0;
        $canvassing->status = 4;//initialize in unapprove status
        $canvassing->save();

        foreach ($tempVillages as $tempVillage){
            $village = new CanvassingVillage();
            $village->idgramasewa_division = Village::find(intval($tempVillage->idvillage))->idgramasewa_division;
            $village->idvillage = $tempVillage->idvillage;
            $village->idcanvassing = $canvassing->idcanvassing;
            $village->agent = User::where('idoffice',Auth::user()->idoffice)->whereHas('agent',function ($q) use ($tempVillage){
                $q->where('idvillage',$tempVillage->idvillage);
            })->where('status',1)->first()->idUser;
            $village->status = 2;//initialize in pending status
            $village->save();
            $tempVillage->delete();
        }

        return response()->json(['success' => 'success']);
    }

    public function canvassingType(){

        return view('canvassing.canvassing_type', ['title' =>  __('Canvassing Type')]);
    }

    public function saveCanvassingType(Request $request){
        $validationMessages = [
            'canvassing_type.required' => 'Canvassing type should be provided!',
            'canvassing_type_si.required' => 'Canvassing type in sinhala should be provided!',
            'canvassing_type_ta.required' => 'Canvassing type in tamil should be provided!'
        ];

        $validator = \Validator::make($request->all(), [
            'canvassing_type' => 'required',
            'canvassing_type_si' => 'required',
            'canvassing_type_ta' => 'required'
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $canvassingType  = CanvassingType::where('name_en',$request['canvassing_type'])->where('status',1)->first();

        if($canvassingType == null){
            $canvassingType = new CanvassingType();
            $canvassingType->name_en = $request['canvassing_type'];
            $canvassingType->name_si = $request['canvassing_type_si'];
            $canvassingType->name_ta = $request['canvassing_type_ta'];
            $canvassingType->idoffice = Auth::user()->idoffice;
            $canvassingType->status = 1;
            $canvassingType->save();
        }
        else{
            return response()->json(['errors' => ['error'=>'Canvassing Type Already Exist.']]);
        }
        return response()->json(['success' => 'Canvassing type saved']);

    }

    public function getCanvassingTypeByOffice(Request $request){

        $office  = Auth::user()->idoffice;
        $canvassingTypesCount = CanvassingType::where('status',1)->orderBy('name_en')->count();
        $canvassingTypes = CanvassingType::where('status',1)->orderBy('name_en')->take(15)->get();
        return response()->json(['success'  =>$canvassingTypes,'count'=>$canvassingTypesCount]);

    }

    public function deleteCanvassingType(Request $request){
        $id  = $request['id'];
        $record  =  CanvassingType::find(intval($id));
        $notUsed = true;
        if($notUsed){
            $record->delete();
            return response()->json(['success' => 'Record deleted']);
        }
        return response()->json(['errors' => ['error'=>'This canvassing type has used.Can not be deleted.']]);
    }

    public function updateCanvassingType(Request $request){
        $validationMessages = [
            'updateId.required' => 'Update process has failed!',
            'canvassing_type.required' => 'Canvassing type should be provided!',
            'canvassing_type_si.required' => 'Canvassing type in sinhala should be provided!',
            'canvassing_type_ta.required' => 'Canvassing type in tamil should be provided!'
        ];

        $validator = \Validator::make($request->all(), [
            'updateId' => 'required',
            'canvassing_type' => 'required',
            'canvassing_type_si' => 'required',
            'canvassing_type_ta' => 'required'
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $canvassingType  = CanvassingType::where('idcanvassing_type',$request['updateId'])->first();

        if($canvassingType != null){
            $canvassingType->name_en = $request['canvassing_type'];
            $canvassingType->name_si = $request['canvassing_type_si'];
            $canvassingType->name_ta = $request['canvassing_type_ta'];
            $canvassingType->save();
            return response()->json(['success' => 'Canvassing type updated']);
        }
        else{
            return response()->json(['errors' => ['error'=>'Canvassing Type Not Found.']]);
        }
    }

    public function deleteTempValues(){
        CanvassingVillageTemp::where('idUser',Auth::user()->idUser)->delete();
    }


    public function approve(Request $request){
        $canvassing = Canvassing::where('idcanvassing', $request['id'])->where('idoffice', Auth::user()->idoffice)->first();
        if($canvassing->status == 4){
            $canvassing->status = 2;
            $canvassing->published_at = date('Y-m-d');
            $canvassing->save();

            $this->notifyAllUsers($request['id']);

            return response()->json(['success' => 'success']);
        }
    }

    public function reject(Request $request){
        $canvassing = Canvassing::where('idcanvassing', $request['id'])->where('idoffice', Auth::user()->idoffice)->first();
        if($canvassing->status == 4 || $canvassing->status == 2){
            $canvassing->status = 0;
            $canvassing->save();
            return response()->json(['success' => 'success']);
        }
    }

    public function getAppUsers($canvassingId){

        $canvassingVillages = CanvassingVillage::where('idcanvassing',$canvassingId)->where('status',2)->get();
        $users = [];
        foreach ($canvassingVillages as $canvassingVillage){
            $villageId = $canvassingVillage->idvillage;
            $user = User::where('idoffice',Auth::user()->idoffice)->whereHas('agent',function ($q) use($villageId){
                $q->where('idvillage',$villageId)->where('isSms',0);
            })->orWhereHas('member',function ($q) use($villageId){
                $q->whereHas('memberAgents',function ($query){
                   $query->where('idoffice',Auth::user()->idoffice)->where('status',1);
                })->where('isSms',0)->where('idvillage',$villageId);
            })->where('status',1)->get();
            array_push($users,$user);
        }
        return $users;
    }

    public function getSmsUsers($canvassingId){

        $canvassingVillages = CanvassingVillage::where('idcanvassing',$canvassingId)->where('status',1)->get();
        $users = [];
        foreach ($canvassingVillages as $canvassingVillage){
            $villageId = $canvassingVillage->idvillage;

            $user = User::where('idoffice',Auth::user()->idoffice)->whereHas('agent',function ($q) use($villageId){
                $q->where('idvillage',$villageId)->where('isSms',1);
            })->orWhereHas('member',function ($q) use($villageId){
                $q->whereHas('memberAgents',function ($query) use($villageId){
                    $query->where('idoffice',Auth::user()->idoffice)->where('status',1);
                })->where('isSms',1)->where('idvillage',$villageId);
            })->where('status',1)->get();
            array_push($users,$user);

        }
        return $users;

    }
    public function unapproved(Request $request){

        $query  = Canvassing::query();
        if (!empty($request['name'])) {
            $query = $query->whereHas('village',function ($q) use($request){
                $q->whereHas('village',function ($q) use($request){
                    $q->where('name_en', 'like', '%' . $request['name'] . '%');
                });
            });
        }
        if ($request['type'] != null) {
            $query = $query->where('idcanvassing_type', $request['type']);
        }
        if (!empty($request['start']) && !empty($request['end'])) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end'] . ' +1 day'));

            $query = $query->whereBetween('date', [$startDate, $endDate]);
        }
        if(Auth::user()->iduser_role == 5){
            $canvassing = $query->where('idUser',Auth::user()->idUser)->where('status',4)->paginate(20);

        }
        else{
            $canvassing = $query->where('idoffice',Auth::user()->idoffice)->where('status',4)->paginate(20);

        }
        $canvassingTypes = CanvassingType::where('status',1)->latest()->get();
        return view('canvassing.unapproved', ['title' =>  __('Unapproved Canvassing'),'canvassings'=>$canvassing,'canvassingTypes'=>$canvassingTypes]);
    }

    public function notifyAllUsers($canvassingId){
        $appUsers =  $this->getAppUsers($canvassingId);
        foreach ($appUsers as $appUser){
            foreach ($appUser as $user){
            app(ApiNotificationController::class)->sendNotification($user->idUser,'New Canvassing Published','You have been assigned for new canvassing','','');
        }
        }

        $smsUsers = $this->getSmsUsers($canvassingId);
        foreach ($smsUsers as $smsUser){
        foreach ($smsUser as $user){
            app(SmsController::class)->sendNotification('You have assigned to new canvassing.Contact your agent for more information',$user->contact_no1);
        }
        }
    }

    public function pending(Request $request){

        $query  = Canvassing::query();
        if (!empty($request['name'])) {
            $query = $query->whereHas('village',function ($q) use($request){
                $q->whereHas('village',function ($q) use($request){
                    $q->where('name_en', 'like', '%' . $request['name'] . '%');
                });
            });
        }
        if ($request['type'] != null) {
            $query = $query->where('idcanvassing_type', $request['type']);
        }
        if (!empty($request['start']) && !empty($request['end'])) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end'] . ' +1 day'));

            $query = $query->whereBetween('date', [$startDate, $endDate]);
        }
        if(Auth::user()->iduser_role == 5){
            $canvassing = $query->where('idUser',Auth::user()->idUser)->where('status',2)->paginate(20);

        }
        else{
            $canvassing = $query->where('idoffice',Auth::user()->idoffice)->where('status',2)->paginate(20);

        }
        $canvassingTypes = CanvassingType::where('status',1)->latest()->get();
        return view('canvassing.pending', ['title' =>  __('Pending Canvassing'),'canvassings'=>$canvassing,'canvassingTypes'=>$canvassingTypes]);
    }

    public function rejected(Request $request){

        $query  = Canvassing::query();
        if (!empty($request['name'])) {
            $query = $query->whereHas('village',function ($q) use($request){
                $q->whereHas('village',function ($q) use($request){
                    $q->where('name_en', 'like', '%' . $request['name'] . '%');
                });
            });
        }
        if ($request['type'] != null) {
            $query = $query->where('idcanvassing_type', $request['type']);
        }
        if (!empty($request['start']) && !empty($request['end'])) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end'] . ' +1 day'));

            $query = $query->whereBetween('date', [$startDate, $endDate]);
        }
        if(Auth::user()->iduser_role == 5){
            $canvassing = $query->where('idUser',Auth::user()->idUser)->where('status',0)->paginate(20);

        }
        else{
            $canvassing = $query->where('idoffice',Auth::user()->idoffice)->where('status',0)->paginate(20);

        }
        $canvassingTypes = CanvassingType::where('status',1)->latest()->get();
        return view('canvassing.rejected', ['title' =>  __('Rejected Canvassing'),'canvassings'=>$canvassing,'canvassingTypes'=>$canvassingTypes]);
    }

    public function finished(){

        $canvassingTypes = CanvassingType::where('status',1)->latest()->get();
        return view('canvassing.finished', ['title' =>  __('Finished Canvassing'),'canvassingTypes'=>$canvassingTypes]);
    }

    public function finishedTable(Request $request){
        $query  = Canvassing::query();
        if (!empty($request['name'])) {
            $query = $query->whereHas('village',function ($q) use($request){
                $q->whereHas('village',function ($q) use($request){
                    $q->where('name_en', 'like', '%' . $request['name'] . '%');
                });
            });
        }
        if ($request['type'] != null) {
            $query = $query->where('idcanvassing_type', $request['type']);
        }
        if (!empty($request['start']) && !empty($request['end'])) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end'] . ' +1 day'));

            $query = $query->whereBetween('date', [$startDate, $endDate]);
        }
        $canvassings = $query->where('idoffice',Auth::user()->idoffice)->where('status',3)->orderBy('canvassingNo','DESC')->get();
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
            $response['date'] = $canvassing->date;
            $response['published_at'] = $canvassing->published_at;
            $response['no'] = sprintf("%05d",$canvassing->canvassingNo);
            $response['villages'] = $villagesNames;
            $response['finished'] = Carbon::parse($canvassing->finished_at)->format('H:i:s');
            $response['started'] = Carbon::parse($canvassing->started_at)->format('H:i:s');
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

    public function getVotingData(Request $request){
        $district = Auth::user()->office->iddistrict;

        $houses = HouseDynamic::whereHas('house',function ($q)use ($district){
            $q->whereHas('village',function ($q)use ($district){
                $q->where('status',1);
            })->where('status',1)->where('idoffice',Auth::user()->idoffice);
        })->where('status',1)->get();
        foreach ($houses as $house){
            $house['lat'] = $house->house->lat;
            $house['long'] = $house->house->long;
        }
        return response()->json(['success' => $houses]);
    }

}
