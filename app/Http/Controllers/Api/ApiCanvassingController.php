<?php

namespace App\Http\Controllers\Api;

use App\Agent;
use App\AgentMapLocation;
use App\Canvassing;
use App\CanvassingAttendance;
use App\CanvassingAttendanceForecasting;
use App\CanvassingVillage;
use App\House;
use App\HouseCondition;
use App\HouseDynamic;
use App\HouseMember;
use App\MemberAgent;
use App\User;
use App\Village;
use App\VotersCount;
use App\VotingCondition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiCanvassingController extends Controller
{
     /*
     *Get canvassing data belongs to member and agent
     */
    public function index(Request $request){
        $apiLang = $request['lang'];
        $fallBack = ['name_en','location_en','description_en'];
        if ($apiLang == 'si') {
            $lang = ['name_si','location_si','description_si'];
        } elseif ($apiLang == 'ta') {
            $lang = ['name_ta','location_ta','description_ta'];
        } else {
            $lang = ['name_en','location_en','description_en'];
        }

        if(Auth::user()->iduser_role == 6){
            $fullCollection = Canvassing::where('idoffice',Auth::user()->idoffice)->whereHas('village',function ($q){
                $q->where('idVillage',Auth::user()->agent->idvillage)->whereIn('status',[1,2]);
            })->whereIn('status',[1,2])->latest()->get();
        }
        else if(Auth::user()->iduser_role == 7){
            $fullCollection = Canvassing::whereHas('village',function ($q){
                $q->where('idVillage',Auth::user()->member->idvillage)->where('idoffice',Auth::user()->member->currentOffice())->whereIn('status',[1,2]);
            })->whereIn('status',[1,2])->latest()->get();
        }

        $getCanvassing = $this->filterLanguage($fullCollection,$lang,$fallBack,'idcanvassing');

        return response()->json(['success' =>$getCanvassing,'statusCode'=>0]);
    }


    /*
     *Get canvassing data belongs to member and agent
     */
    public function filterLanguage($collection, $lang, $fallBack, $id)
    {
        foreach ($collection as $item) {
            $item['location'] = $item[$lang[1]] != null ? $item[$lang[1]] : $item[$fallBack[1]];
            $item['name'] = $item[$lang[0]] != null ? $item[$lang[0]] : $item[$fallBack[0]];
            $item['description'] = $item[$lang[2]] != null ? $item[$lang[2]] : $item[$fallBack[2]];
            $item['id'] = $item[$id];
                if(Auth::user()->iduser_role == 6){
                    $item['status'] = CanvassingVillage::where('idcanvassing',$item->idcanvassing)->where('agent',Auth::user()->idUser)->latest()->first()->status;

                }
                else{
                    $item['status'] = CanvassingVillage::where('idcanvassing',$item->idcanvassing)->where('agent',Agent::find(Auth::user()->member->current_agent)->userBelongs->idUser)->latest()->first()->status;

                }
            $item['type'] = 'Canvassing Type';
            $villages = CanvassingVillage::where('idcanvassing',$item->idcanvassing)->get();

            $name  = $lang[0];
            foreach ($villages as $village) {
                $village['name'] = $village->village->$name;
                $village['villageId'] = $village->idvillage;
                $village->makeHidden('updated_at')->toArray();
                $village->makeHidden('created_at')->toArray();
                $village->makeHidden('idcanvassing_village')->toArray();
                $village->makeHidden('idgramasewa_division')->toArray();
                $village->makeHidden('idcanvassing')->toArray();
                $village->makeHidden('idUser')->toArray();
                $village->makeHidden('long')->toArray();
                $village->makeHidden('lat')->toArray();
                $village->makeHidden('status')->toArray();
                $village->makeHidden('idvillage')->toArray();
                $village->makeHidden('village')->toArray();
            }
            $item['village'] = $villages;

            unset($item->$id);
            $item->makeHidden('name_en')->toArray();
            $item->makeHidden('name_ta')->toArray();
            $item->makeHidden('name_si')->toArray();
            $item->makeHidden('location_en')->toArray();
            $item->makeHidden('location_ta')->toArray();
            $item->makeHidden('location_si')->toArray();
            $item->makeHidden('description_en')->toArray();
            $item->makeHidden('description_ta')->toArray();
            $item->makeHidden('description_si')->toArray();
            $item->makeHidden('idoffice')->toArray();
            $item->makeHidden('idUser')->toArray();
            $item->makeHidden('idelection_division')->toArray();
            $item->makeHidden('idpolling_booth')->toArray();
            $item->makeHidden('idgramasewa_division')->toArray();
            $item->makeHidden('updated_at')->toArray();
            $item->makeHidden('idcanvassing_type')->toArray();
        }
        return $collection;
    }


    /*
    *Start canvassing ann return canvassing data
     * returning canvassing data = return from getData() function
    */
    public function start(Request $request){

        $validationMessages = [
            'id.required' => 'Id is required!',
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required|numeric',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }

        if( $this->onGoingCanvassingExist(Auth::user()->idUser)!= -1){
            return response()->json(['error' => 'Previous canvassing is not stopped.', 'statusCode' => -99]);
        }

        $canvassing  = Canvassing::where('idoffice',Auth::user()->idoffice)->where('idcanvassing',intval($request['id']))->first();
        if($canvassing == null ){
            return response()->json(['error' => 'Canvassing not found', 'statusCode' => -99]);
        }

        if($canvassing->village()->where('agent',Auth::user()->idUser)->first() != null) {
            if ($canvassing->village()->where('agent', Auth::user()->idUser)->first()->status != 2) {
                return response()->json(['error' => 'Canvassing is not in pending state', 'statusCode' => -99]);
            }
        }else{
            return response()->json(['error' => 'Canvassing villages not found', 'statusCode' => -99]);
        }

        $apiLang = $request['lang'];
        $fallBack = ['name_en','location_en','description_en'];
        if ($apiLang == 'si') {
            $lang = ['name_si','location_si','description_si'];
        } elseif ($apiLang == 'ta') {
            $lang = ['name_ta','location_ta','description_ta'];

        } else {
            $lang = ['name_en','location_en','description_en'];

        }
        if($canvassing->village()->where('agent',Auth::user()->idUser)->first()->status != 2){
            return response()->json(['error' => 'Canvassing is not in pending state', 'statusCode' => -99]);
        }
        //-------------------------validation end-------------------------------------//

        $village = CanvassingVillage::where('idcanvassing',intval($request['id']))->where('agent',Auth::user()->idUser)->latest()->first();
        $village->status = 1;
        $village->save();

        /* Change canvassing status when first village agent start */
        if($canvassing->status == 2){
            $canvassing->status = 1;
            $canvassing->started_at = now();
            $canvassing->save();
        }

        $success['canvassingId'] = $request['id'];
        $success['attendance_list'] = $this->attendanceList($request['id']);
        $success['voting_conditions'] = $this->votingConditions($lang,$fallBack);
        $success['house_conditions'] = $this->getHouseConditions($lang,$fallBack);
        $success['house_members'] = $this->getVoterTypes($lang,$fallBack);
        $success['formInDetail'] = $canvassing->formInDetail;
        $success['status'] = $canvassing->status;
        $housesSummery = $this->getHousesCount(intval($request['id']),Auth::user()->agent->idvillage,Auth::user()->idoffice);
        $success['finishedHouses'] = $housesSummery['finished'];
        $success['totalHouses'] = $housesSummery['total'];

        return response()->json(['success' => $success,'statusCode'=>0]);

    }


    /*
     * Get canvassing data for persist in mobile app
     * returning canvassing data = return from start() function
     */
    public function getData(Request $request){
        $validationMessages = [
            'id.required' => 'Id is required!',
            'lang.required' => 'Please provide user language!',
            'lang.in' => 'User language invalid!',
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required|numeric',
            'lang' => 'required|in:en,si,ta',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }
        $canvassing  = Canvassing::where('idoffice',Auth::user()->idoffice)->where('idcanvassing',intval($request['id']))->first();
        if($canvassing == null ){
            return response()->json(['error' => 'Canvassing not found', 'statusCode' => -99]);
        }
        $myVillage = $canvassing->village()->where('agent',Auth::user()->idUser)->first();
        if( $myVillage == null || $myVillage->status != 1){
            return response()->json(['error' => 'Canvassing is not on going', 'statusCode' => -99]);
        }

        $apiLang = $request['lang'];
        $fallBack = ['name_en','location_en','description_en'];
        if ($apiLang == 'si') {
            $lang = ['name_si','location_si','description_si'];
        } elseif ($apiLang == 'ta') {
            $lang = ['name_ta','location_ta','description_ta'];

        } else {
            $lang = ['name_en','location_en','description_en'];

        }
        //-------------------------validation end-------------------------------------//

        $village = CanvassingVillage::where('idcanvassing',intval($request['id']))->where('agent',Auth::user()->idUser)->latest()->first();
        $village->status = 1;
        $village->save();

        $success['canvassingId'] = $request['id'];
        $success['formInDetail'] = $canvassing->formInDetail;
        $success['house_conditions'] = $this->getHouseConditions($lang,$fallBack);
        $success['attendance_list'] = $this->attendanceList($request['id']);
        $success['voting_conditions'] = $this->votingConditions($lang,$fallBack);
        $success['house_members'] = $this->getVoterTypes($lang,$fallBack);
        $success['status'] = $canvassing->status;
        $housesSummery = $this->getHousesCount(intval($request['id']),Auth::user()->agent->idvillage,Auth::user()->idoffice);
        $success['finishedHouses'] = $housesSummery['finished'];
        $success['totalHouses'] = $housesSummery['total'];

        return response()->json(['success' => $success,'statusCode'=>0]);
    }


    /*
     * Get members list belongs to agent with attend status for mark attendance
     */
    public function attendanceList($id){

        if(Auth::user()->iduser_role != 6){
            return response()->json(['error' => 'You are not an agent','statusCode'=>-99]);
        }

        $user  = Auth::user();
        $membersAgents = MemberAgent::where('idagent',$user->agent->idagent)->where('status',1)->get();

        foreach ( $membersAgents as $member){

            $member['id'] = $member->member->idUser;
            $member['name'] = $member->member->belongsUser->fName;
            $member['isAttend'] = CanvassingAttendance::where('idcanvassing',$id)->where('idUser',$member->member->idUser)->where('status',1)->exists();

            $member->makeHidden('created_at')->toArray();
            $member->makeHidden('idmember_agent')->toArray();
            $member->makeHidden('idmember')->toArray();
            $member->makeHidden('idagent')->toArray();
            $member->makeHidden('idoffice')->toArray();
            $member->makeHidden('status')->toArray();
            $member->makeHidden('member')->toArray();
        }
        return $membersAgents;
    }


    /*
     * Mark attendance from attendance array.
     */
    public function markAttendance(Request $request){

        $attendancesArray = $request['attendance'];
        $canvassingId = $request['canvassingId'];
        $canvassing = Canvassing::find(intval($canvassingId));

        if($canvassing == null ||  $canvassing->status != 1 ){
            return response()->json(['error' => 'Canvassing not in ongoing status.', 'statusCode' => -99]);
        }

        if($attendancesArray == null){
            return response()->json(['error' => 'Invalid Request', 'statusCode' => -99]);
        }
        else {
            foreach ($attendancesArray as $attendances) {

                if (!isset($attendances['id'] ) || !isset($attendances['isAttend']) || !isset($attendances['updated_at'] ) ||
                    $attendances['id']  == null  ||  $attendances['updated_at'] == null) {
                    return response()->json(['error' => 'Insufficient parameters', 'statusCode' => -99]);
                }
                if(Canvassing::find(intval($canvassingId)) == null ){
                    return response()->json(['error' => 'Canvassing not found .', 'statusCode' => -99]);
                }
            }
        }


        foreach ($attendancesArray as $attendances){
            $attendance = CanvassingAttendance::where('idUser', $attendances['id'] )->where('idcanvassing',$canvassingId)->first();
            if($attendance == null){
                $attendance = new CanvassingAttendance();
                $attendance->idUser = $attendances['id'] ;
                $attendance->idvillage = User::find(intval($attendances['id']))->getType->idvillage ;
                $attendance->idcanvassing = $canvassingId;
            }
            $attendance->status = $attendances['isAttend'];
            $attendance->save();
        }
        return response()->json(['success' =>'Attendance marked successfully' ,'statusCode'=>0]);
    }


    public function markHouse(Request  $request){

        $housesArray = $request['houses'];
        $canvassingId = $request['canvassingId'];
        $canvassing = Canvassing::find(intval($canvassingId));

        if($canvassing == null ||  $canvassing->status != 1 ){
            return response()->json(['error' => 'Canvassing not in ongoing status.', 'statusCode' => -99]);
        }

        if($housesArray == null){
            return response()->json(['error' => 'Invalid Request', 'statusCode' => -99]);
        }
        else {
            foreach ($housesArray as $houses) {
                if (!isset($houses['houseNo'])|| $houses['houseNo'] == null) {
                    return response()->json(['error' => 'House no should be provided', 'statusCode' => -99]);
                }
            }
        }
        $agent = Auth::user()->agent;
        $idvillage = $agent->idvillage;

        foreach ($housesArray as $houses) {
            $selectedCanvassing = Canvassing::find(intval($canvassingId));
            if($agent == null){
                return response()->json(['error' => 'Agent not found', 'statusCode' => -99]);
            }
            if($selectedCanvassing == null){
                return response()->json(['error' => 'Canvassing not found', 'statusCode' => -99]);
            }



            $house = House::where('idvillage', $idvillage)->where('idoffice',Auth::user()->idoffice)->where('houseNo', $houses['houseNo'])->first();
            if ($house == null) {
                $house = new House();
                $house->idvillage = $idvillage;
                $house->idoffice = Auth::user()->idoffice;
                $house->houseNo = $houses['houseNo'];
                $house->status = 1;
            }
            if(isset($houses['houseName']) && $houses['houseName'] != null &&  $house['houseName'] == null){
                $house['houseName'] = $houses['houseName'];
            }

            $house->idhouse_condition = $houses['condition'];
            $house->visited_at = $houses['visited_at'];
            $house->elderVoters = $houses['eldersTotal'];
            $house->youngVoters = $houses['youngTotal'];
            $house->firstVoters = $houses['firstTotal'];
            if($house['lat'] == null && $house['long'] == null){
                $house->lat = $houses['lat'];
                $house->long = $houses['long'];
            }
            $house->save();

            if($selectedCanvassing->formInDetail == 1 && $houses['formDetails'] != null){
            $formDetails = $houses['formDetails'];
                HouseDynamic::where('idhouse', $house->idhouse)->where('idcanvassing',$canvassingId)->delete();
                foreach ($formDetails as $formDetail){
                    if($formDetail['count'] > 0){
                        $houseDynamic = new HouseDynamic();
                        $houseDynamic->idcanvassing = $canvassingId;
                        $houseDynamic->idhouse = $house->idhouse;
                        $houseDynamic->idvoting_condition = $formDetail['voting_condition'];
                        $houseDynamic->idhouse_member = $formDetail['house_member'];
                        $houseDynamic->count = $formDetail['count'];
                        $houseDynamic->status = 1;
                        $houseDynamic->save();
                    }
             }
            }
        }

        $housesSummery = $this->getHousesCount($canvassingId,$idvillage,Auth::user()->idoffice);
        $finishedHouses = $housesSummery['finished'];
        $totalHouses = $housesSummery['total'];

        return response()->json(['success' =>['finishedHouses'=>$finishedHouses,'totalHouses'=>$totalHouses] ,'statusCode'=>0]);
    }

    public function getHouse(Request $request){
        $validationMessages = [
            'houseNo.required' => 'House No is required!',
            'lang.in' => 'User language invalid!',
        ];

        $validator = \Validator::make($request->all(), [
            'houseNo' => 'required',
            'lang' => 'required|in:en,si,ta',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }
        $apiLang = $request['lang'];
        $fallBack = ['name_en','location_en','description_en'];
        if ($apiLang == 'si') {
            $lang = ['name_si','location_si','description_si'];
        } elseif ($apiLang == 'ta') {
            $lang = ['name_ta','location_ta','description_ta'];

        } else {
            $lang = ['name_en','location_en','description_en'];

        }

        $house = House::where('idvillage',Auth::user()->agent->idvillage)->where('houseNo',$request['houseNo'])->where('status',1)->first();
        $language = $lang[0];
        if($house!= null) {
            $house['house_condition'] = ["id"=>$house->idhouse_condition,"label"=>HouseCondition::find(intval($house->idhouse_condition))->$language];

            $house->makeHidden('idhouse')->toArray();
            $house->makeHidden('created_at')->toArray();
            $house->makeHidden('idvillage')->toArray();
            $house->makeHidden('updated_at')->toArray();
            $house->makeHidden('elderVoters')->toArray();
            $house->makeHidden('youngVoters')->toArray();
            $house->makeHidden('firstVoters')->toArray();
            $house->makeHidden('idoffice')->toArray();
            $house->makeHidden('houseNo')->toArray();
            $house->makeHidden('idhouse_condition')->toArray();
            $house->makeHidden('visited_at')->toArray();
            $house->makeHidden('lat')->toArray();
            $house->makeHidden('long')->toArray();
            $house->makeHidden('status')->toArray();

            return response()->json(['success' => $house, 'statusCode' => 0]);
        }
        else{
            return response()->json(['error' => 'House not found', 'statusCode' => -99]);
        }
    }

    public function updateMap(Request $request){

        $validationMessages = [
            'lat.required' => 'Latitude is required!',
            'long.required' => 'Longitude is required!',
            'canvassingId.required' => 'Canvassing id is required!',
        ];

        $validator = \Validator::make($request->all(), [
            'lat' => 'required',
            'long' => 'required',
            'canvassingId' => 'required',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }

        $map  = new AgentMapLocation();
        $map->idcanvassing = $request['canvassingId'];
        $map->idUser = Auth::user()->idUser;
        $map->lat  = $request['lat'];
        $map->long = $request['long'];
        $map->status = 1;
        $map->save();
        return response()->json(['success' =>'saved' ,'statusCode'=>0]);

    }

    public function attendanceForecasting(Request $request){
        $validationMessages = [
            'isMark.required' => 'Status is required!',
            'canvassingId.required' => 'Canvassing id is required!',
        ];

        $validator = \Validator::make($request->all(), [
            'isMark' => 'required',
            'canvassingId' => 'required',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }

        $forecasting = CanvassingAttendanceForecasting::where('idUser',Auth::user()->idUser)->where('idcanvassing',$request['canvassingId'])->first();
        if($forecasting == null){
            $forecasting = new CanvassingAttendanceForecasting();
            $forecasting->idUser = Auth::user()->idUser;
            $forecasting->idcanvassing = $request['canvassingId'];
            $forecasting->user_type = Auth::user()->iduser_role;
            $forecasting->idvillage = Auth::user()->getType->idvillage;
        }
        $forecasting->status = $request['isMark'];
        $forecasting->save();
        return response()->json(['success' =>'Forecasting saved successfully' ,'statusCode'=>0]);
    }


    /*
    * Get voting condition in selected language
    */
    public function votingConditions($lang,$fallBack){

        if(Auth::user()->iduser_role != 6){
            return response()->json(['error' => 'You are not an agent','statusCode'=>-99]);
        }

       $votingConditions = VotingCondition::where('status',1)->get();

        foreach ( $votingConditions as $votingCondition){

            $votingCondition['id'] = $votingCondition->idvoting_condition;
            $votingCondition['label'] = $votingCondition[$lang[0]] != null ? $votingCondition[$lang[0]] : $votingCondition[$fallBack[0]];

            $votingCondition->makeHidden('created_at')->toArray();
            $votingCondition->makeHidden('updated_at')->toArray();
            $votingCondition->makeHidden('status')->toArray();
            $votingCondition->makeHidden('name_en')->toArray();
            $votingCondition->makeHidden('name_si')->toArray();
            $votingCondition->makeHidden('name_ta')->toArray();
            $votingCondition->makeHidden('idvoting_condition')->toArray();
        }
        return $votingConditions;
    }


    /*
     * Get house conditions in selected language
     */
    public function getHouseConditions($lang,$fallBack){

        $conditions = HouseCondition::where('status',1)->get();
        foreach ($conditions as $condition) {
            $condition['label'] = $condition[$lang[0]] != null ? $condition[$lang[0]] : $condition[$fallBack[0]];
            $condition['id'] = $condition['idhouse_condition'];

            unset($condition->idhouse_condition);
            $condition->makeHidden('name_en')->toArray();
            $condition->makeHidden('name_ta')->toArray();
            $condition->makeHidden('name_si')->toArray();
            $condition->makeHidden('updated_at')->toArray();
            $condition->makeHidden('created_at')->toArray();
            $condition->makeHidden('status')->toArray();
        }
        return $conditions;
    }


    /*
    * Get voters type eg: Elder, young, firstVoters
    */
    public function getVoterTypes($lang,$fallBack){

        $members = HouseMember::where('status',1)->get();
        foreach ($members as $member) {
            $member['label'] = $member[$lang[0]] != null ? $member[$lang[0]] : $member[$fallBack[0]];
            $member['id'] = $member['idhouse_condition'];

            unset($member->idhouse_member);
            $member->makeHidden('name_en')->toArray();
            $member->makeHidden('name_ta')->toArray();
            $member->makeHidden('name_si')->toArray();
            $member->makeHidden('updated_at')->toArray();
            $member->makeHidden('created_at')->toArray();
            $member->makeHidden('status')->toArray();
        }
        return $members;
    }



    public function stopCanvassing(Request $request){
        $validationMessages = [
            'id.required' => 'Id is required!',
            'finished_at.required' => 'Finished time is required!',
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required|numeric',
            'finished_at' => 'required',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }

        $canvassing  = Canvassing::where('idoffice',Auth::user()->idoffice)->where('idcanvassing',intval($request['id']))->first();
        if($canvassing == null ){
            return response()->json(['error' => 'Canvassing not found', 'statusCode' => -99]);
        }

        $canvasingVillage  = $canvassing->village()->where('agent',Auth::user()->idUser)->first();
        if($canvasingVillage == null) {
            return response()->json(['error' => 'Canvassing villages not found', 'statusCode' => -99]);
        }
        if ($canvasingVillage->status == 1) {
            $canvasingVillage->status = 3;
            $canvasingVillage->finished_at = $request['finished_at'];
            $canvasingVillage->save();
        }

        $ongoingVillages =  $canvassing->village()->whereIn('status',[2,1])->exists();
        if($ongoingVillages != 1){
            $canvassing->status = 3;
            $canvassing->finished_at = $request['finished_at'];
            $canvassing->save();
        }

        foreach ($canvassing->village as $village) {
            $totalHouses = House::where('idoffice', Auth::user()->idoffice)->where('status', 1)->where('idvillage', $village->idvillage)->count();
            $votersCount = VotersCount::where('idvillage',$village->idvillage)->where('idoffice',Auth::user()->idoffice)->first();
            if($votersCount != null && ($votersCount->status == 2 || $votersCount->status == 1)){
                $votersCount->status = 1;
                $votersCount->houses = intval($totalHouses);
                $votersCount->save();
            }
        }
        return response()->json(['success' => 'Canvassing Stopped' , 'statusCode' => 0]);
    }

    public function onGoingCanvassingExist($userId = null){
        if($userId == null){
            $user = Auth::user();

        }
        else{
            $user = User::find(intval($userId));

        }
        if($user->iduser_role == 6){
            $isCanvassingExist = Canvassing::where('idoffice',$user->idoffice)->whereHas('village',function ($q) use ($user){
                $q->where('idVillage',$user->agent->idvillage)->where('status',1);
            })->latest()->first();
        }
        else if($user->iduser_role == 7){
            $isCanvassingExist = Canvassing::whereHas('village',function ($q)  use ($user){
                $q->where('idVillage',$user->member->idvillage)->where('idoffice',$user->member->currentOffice())->where('status',1);
            })->latest()->first();
        }
        else{
            $isCanvassingExist = null;
        }

        return $isCanvassingExist == null ? -1 : $isCanvassingExist->idcanvassing;
    }

    public function getHousesCount($canvassingId,$villageId,$officeId){
        $votes = Village::find(intval($villageId))->votes()->where('idoffice',$officeId)->first();
        $totalHouses = $votes == null ? 0 : $votes->houses;

        $finishedHouses = House::where('idvillage', $villageId)->where('idoffice',$officeId)->whereHas('houseDynamics',function ($q) use ($canvassingId){
            $q->where('idcanvassing',$canvassingId)->where('status',1);
        })->where('status',1)->count();

        $response['total'] = $totalHouses;
        $response['finished'] = $finishedHouses;
        return $response;
    }

    public function history(Request $request){
        $apiLang = $request['lang'];
        $fallBack = ['name_en','location_en','description_en'];
        if ($apiLang == 'si') {
            $lang = ['name_si','location_si','description_si'];
        } elseif ($apiLang == 'ta') {
            $lang = ['name_ta','location_ta','description_ta'];
        } else {
            $lang = ['name_en','location_en','description_en'];
        }

        $endDate = $request['end'];
        $startDate = $request['start'];
        $query = Canvassing::query();

        if(Auth::user()->iduser_role == 6){
            $query->where('idoffice',Auth::user()->idoffice)->whereHas('village',function ($q){
                $q->where('idVillage',Auth::user()->agent->idvillage);
            });
        }
        else if(Auth::user()->iduser_role == 7){
            $query->whereHas('village',function ($q){
                $q->where('idVillage',Auth::user()->member->idvillage)->where('idoffice',Auth::user()->member->currentOffice());
            });
        }
        else{
            return response()->json(['error' => 'User Invalid', 'statusCode' => -99]);
        }

        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end'].'+1 day'));

            $query = $query->whereBetween('started_at', [$startDate, $endDate]);
        }

        $canvassings = $query->where('status',3)->latest()->get();

        foreach ($canvassings as $canvassing){
            $housesSummery = $this->getHousesCount($canvassing->idcanvassing,Auth::user()->getType->idvillage,Auth::user()->idoffice);
            $canvassing['finishedHouses'] = $housesSummery['finished'];
            $canvassing['totalHouses'] = $housesSummery['total'];
            $canvassing->makeHidden('published_at')->toArray();
            $canvassing->makeHidden('created_at')->toArray();
            $canvassing->makeHidden('status')->toArray();
            $canvassing->makeHidden('formInDetail')->toArray();
            $canvassing->makeHidden('date')->toArray();
            $canvassing->makeHidden('time')->toArray();
            $canvassing['attendance'] = CanvassingAttendance::where('idvillage',Auth::user()->getType->idvillage)->where('idcanvassing',$canvassing->idcanvassing)->where('status',1)->count();
            $canvassing['totalVoters']  = intval(HouseDynamic::whereHas('house',function ($q){
                $q->where('idvillage',Auth::user()->getType->idvillage);
            })->where('idcanvassing',$canvassing->idcanvassing)->where('status',1)->sum('count'));
        }
        $filteredCanvassing = $this->filterLanguage($canvassings,$lang,$fallBack,'idcanvassing');

        return response()->json(['success' => $filteredCanvassing , 'statusCode' => 0]);
    }
}
