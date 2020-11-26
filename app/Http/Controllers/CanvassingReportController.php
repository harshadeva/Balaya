<?php

namespace App\Http\Controllers;

use App\Canvassing;
use App\CanvassingType;
use App\CanvassingVillage;
use App\ElectionDivision;
use App\GramasewaDivision;
use App\House;
use App\HouseCondition;
use App\HouseDynamic;
use App\HouseMember;
use App\PollingBooth;
use App\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanvassingReportController extends Controller
{
    public function votersCount(Request $request){
        $electionDivisions = ElectionDivision::where('status', 1)->where('iddistrict',Auth::user()->office->iddistrict)->get();
        $houseConditions = HouseCondition::where('status',1)->get();
        $houseMember = HouseMember::where('status',1)->get();
        return view('canvassing.voters_report', ['title' =>  __('Report : Voters Count'),'electionDivisions'=>$electionDivisions,'houseConditions'=>$houseConditions,'votersConditions'=>$houseMember]);
    }

    public function compare(Request $request){
        $electionDivisions = ElectionDivision::where('status', 1)->where('iddistrict',Auth::user()->office->iddistrict)->get();
        $houseConditions = HouseCondition::where('status',1)->get();
        $houseMember = HouseMember::where('status',1)->get();
        $canvassingTypes = CanvassingType::where('status',1)->get();
        return view('canvassing.compare', ['title' =>  __('Report : Compare Canvassing'),'canvassingTypes'=>$canvassingTypes,'electionDivisions'=>$electionDivisions,'houseConditions'=>$houseConditions,'votersConditions'=>$houseMember]);
    }

    public function votersCountChart(Request $request){
        $village = $request['village'];
        $gramasewaDivision = $request['gramasewaDivision'];
        $pollingBooth = $request['pollingBooth'];
        $electionDivision = $request['electionDivision'];
        $district = Auth::user()->office->iddistrict;
        $lastCanvassing = Canvassing::where('idoffice',Auth::user()->idoffice)->latest()->first();
        if($lastCanvassing != null){
            $lastCanvassingID = $lastCanvassing->idcanvassing;
        }
        else{
            $lastCanvassingID = 0;
        }
        $houseCondition = $request['houseCondition'];
        $voterCategory = $request['voterCategory'];

        if ($village != null) {
            $area = $village;
            $areaId = 'idvillage';
            $lowerId= 'idvillage';
            $dropDown= 'village';
            $division = Village::query();
        } else if ($gramasewaDivision != null) {
            $area = $gramasewaDivision;
            $areaId = 'idgramasewa_division';
            $lowerId= 'idvillage';
            $dropDown= 'village';
            $division = Village::query();
        } else if ($pollingBooth != null) {
            $area = $pollingBooth;
            $areaId = 'idpolling_booth';
            $lowerId= 'idgramasewa_division';
            $dropDown= 'gramasewaDivision';
            $division = GramasewaDivision::query();
        } else if ($electionDivision != null) {
            $area = $electionDivision;
            $areaId = 'idelection_division';
            $lowerId= 'idpolling_booth';
            $dropDown= 'pollingBooth';
            $division = PollingBooth::query();
        } else {
            $area = $district;
            $areaId = 'iddistrict';
            $lowerId= 'idelection_division';
            $dropDown= 'electionDivision';
            $division = ElectionDivision::query();
        }

        $lastRowhouseCount = 0;
        $lastRowvotersCount = 0;
        $lastRowflavored = 0;
        $lastRowparty = 0;
        $lastRowopposite = 0;
        $lastRowfloating = 0;
        $lastRowoption1 = 0;
        $lastRowoption2 = 0;
        $lastRowoption3 = 0;
        $lastRowssingCount = 0;

        $items  = $division->where($areaId,$area)->where('status',1)->get();
        foreach ($items as $item){
            $fullAhouseCount = 0;
            $fullAvotersCount = 0;
            $fullAflavored = 0;
            $fullAparty = 0;
            $fullAopposite = 0;
            $fullAfloating = 0;
            $fullAoption1 = 0;
            $fullAoption2 = 0;
            $fullAoption3 = 0;
            $canvassingCount = 0;

            $villages  = Village::where($lowerId, $item->$lowerId)->where('status',1)->get();

            foreach ($villages as $village) {
                $houseCount = 0;
                $votersCount = 0;
                $flavored = 0;
                $party = 0;
                $opposite = 0;
                $floating = 0;
                $option1 = 0;
                $option2 = 0;
                $option3 = 0;

                $dynamicQuery = HouseDynamic::query();
                $houseQuery = House::query();
                if ($houseCondition != null) {
                    $dynamicQuery = $dynamicQuery->whereHas('house', function ($q) use ($houseCondition) {
                        $q->where('idhouse_condition', $houseCondition);
                    });

                    $houseQuery->where('idhouse_condition',$houseCondition);
                }
                if ($voterCategory != null) {
                    $dynamicQuery = $dynamicQuery->where('idhouse_member', $voterCategory);
                    $houseQuery->whereHas('houseDynamics',function ($q) use ($voterCategory){
                        $q->where('idhouse_member', $voterCategory);
                    });
                }

                $houses = $houseQuery->where('idoffice', Auth::user()->idoffice)->where('idvillage', $village->idvillage)->where('status', 1)->get();
                foreach ($houses as $house){
                    $eldersCount = 0;
                    $youngCount =  0;
                    $firstCount =  0;

                    if($voterCategory == 1){
                        $eldersCount = $house->elderVoters;
                    }
                    else if($voterCategory == 2){
                        $youngCount = $house->youngVoters;
                    }
                    else if($voterCategory == 3){
                        $youngCount = $house->firstVoters;
                    }
                    else{
                        $eldersCount = $house->elderVoters;
                        $youngCount = $house->youngVoters;
                        $firstCount = $house->firstVoters;
                    }

                    $houseCount++;
                    $votersCount += $eldersCount + $youngCount + $firstCount;

                    $housesDynamics = $dynamicQuery->where('idcanvassing',$lastCanvassingID)->where('idhouse',$house->idhouse)->where('status', 1)->get();
                    foreach ($housesDynamics as $housesDynamic) {

                        if($housesDynamic->idvoting_condition == 1){
                            $flavored ++;
                        }
                        else if($housesDynamic->idvoting_condition == 2){
                            $party++;
                        }
                        else if($housesDynamic->idvoting_condition == 3){
                            $opposite++;
                        }
                        else if($housesDynamic->idvoting_condition == 4){
                            $option1++;
                        }
                        else if($housesDynamic->idvoting_condition == 5){
                            $option2++;
                        }
                        else if($housesDynamic->idvoting_condition == 6){
                            $option3++;
                        }
                        else if($housesDynamic->idvoting_condition == 7){
                            $floating++;
                        }

                    }
                }
                $fullAvotersCount += $votersCount;
                $fullAhouseCount += $houseCount;
                $fullAflavored += $flavored;
                $fullAparty += $party;
                $fullAopposite += $opposite;
                $fullAoption1 += $option1;
                $fullAoption2 += $option2;
                $fullAoption3 += $option3;
                $fullAfloating += $floating;
                $canvassingCount += CanvassingVillage::where('idvillage',$village->idvillage)->whereHas('canvassing',function ($q){
                    $q->where('idoffice',Auth::user()->idoffice);
                })->count();
            }
            $item['name'] = $item->name_en;
            $item['canvassingCount'] = $canvassingCount;
            if($canvassingCount > 0){
                $item['totalVoters'] = $fullAvotersCount;
                $item['totalHouses'] = $fullAhouseCount;
                $item['flavored'] = $fullAflavored;
                $item['party'] = $fullAparty;
                $item['opposite'] = $fullAopposite;
                $item['option1'] = $fullAoption1;
                $item['option2'] = $fullAoption2;
                $item['option3'] = $fullAoption3;
                $item['floating'] = $fullAfloating;
                $item['percentage'] = $fullAvotersCount > 0 ? round(($fullAflavored/$fullAvotersCount)*100,1) : 0;

                $lastRowhouseCount += $fullAhouseCount;
                $lastRowvotersCount += $fullAvotersCount;
                $lastRowflavored += $fullAflavored;
                $lastRowparty += $fullAparty;
                $lastRowopposite += $fullAopposite;
                $lastRowfloating += $fullAfloating;
                $lastRowoption1 += $fullAoption1;
                $lastRowoption2 += $fullAoption2;
                $lastRowoption3 += $fullAoption3;
                $lastRowssingCount += $canvassingCount;
            }
            else{
                $item['totalVoters'] = '-';
                $item['totalHouses'] = '-';
                $item['flavored'] = '-';
                $item['party'] = '-';
                $item['opposite'] = '-';
                $item['option1'] = '-';
                $item['option2'] = '-';
                $item['option3'] = '-';
                $item['floating'] = '-';
                $item['percentage'] = '-';
            }
            $item['id'] = $item->$lowerId;
            $item['dropDown'] = $dropDown;
        }
        if($lastRowssingCount > 0) {
            $lastRow['totalVoters'] = $lastRowvotersCount;
            $lastRow['flavored'] = $lastRowflavored;
            $lastRow['floating'] = $lastRowfloating;
            $lastRow['opposite'] = $lastRowopposite;
            $lastRow['canvassingCount'] = $lastRowssingCount;
            $lastRow['totalHouses'] = $lastRowhouseCount;
            $lastRow['party'] = $lastRowparty;
            $lastRow['option1'] = $lastRowoption1;
            $lastRow['option2'] = $lastRowoption2;
            $lastRow['option3'] = $lastRowoption3;
            $lastRow['percentage'] = $lastRowvotersCount > 0 ? round(($lastRowflavored/$lastRowvotersCount)*100,1) : 0;
        }
        else{
            $lastRow['totalVoters']  = '-';
            $lastRow['flavored'] = '-';
            $lastRow['percentage'] = '-';
            $lastRow['floating']  = '-';
            $lastRow['opposite']  = '-';
            $lastRow['totalHouses']  = '-';
            $lastRow['party']  = '-';
            $lastRow['option1']  = '-';
            $lastRow['option2']  = '-';
            $lastRow['option3']  = '-';
        }

        return response()->json(['success' => $items,'lastRow'=>$lastRow]);
    }

    public function compareChart(Request $request){
        //validation start
        $validator = \Validator::make($request->all(), [
            'round1' => 'required',
            'round2' => 'required',
        ], [
            'round1.required' => 'Canvassing round should be provided!',
            'round2.required' => 'Compare round should be provided!'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $district = Auth::user()->office->iddistrict;
        $village = $request['village'];
        $gramasewaDivision = $request['gramasewaDivision'];
        $pollingBooth = $request['pollingBooth'];
        $electionDivision = $request['electionDivision'];
        $houseCondition = $request['houseCondition'];
        $voterCategory = $request['voterCategory'];
        $round1 = $request['round1'];
        $round2 = $request['round2'];

        $lastRowhouseCount_A = 0;
        $lastRowvotersCount_A = 0;
        $lastRowflavored_A = 0;
        $lastRowparty_A = 0;
        $lastRowopposite_A = 0;
        $lastRowfloating_A = 0;
        $lastRowoption1_A = 0;
        $lastRowoption2_A = 0;
        $lastRowoption3_A = 0;

        $lastRowhouseCount_B = 0;
        $lastRowvotersCount_B = 0;
        $lastRowflavored_B = 0;
        $lastRowparty_B = 0;
        $lastRowopposite_B = 0;
        $lastRowfloating_B = 0;
        $lastRowoption1_B = 0;
        $lastRowoption2_B = 0;
        $lastRowoption3_B = 0;

        if ($village != null) {
            $area = $village;
            $areaId = 'idvillage';
            $lowerId= 'idvillage';
            $dropDown= 'village';
            $division = Village::query();
        } else if ($gramasewaDivision != null) {
            $area = $gramasewaDivision;
            $areaId = 'idgramasewa_division';
            $lowerId= 'idvillage';
            $dropDown= 'village';
            $division = Village::query();
        } else if ($pollingBooth != null) {
            $area = $pollingBooth;
            $areaId = 'idpolling_booth';
            $lowerId= 'idgramasewa_division';
            $dropDown= 'gramasewaDivision';
            $division = GramasewaDivision::query();
        } else if ($electionDivision != null) {
            $area = $electionDivision;
            $areaId = 'idelection_division';
            $lowerId= 'idpolling_booth';
            $dropDown= 'pollingBooth';
            $division = PollingBooth::query();
        } else {
            $area = $district;
            $areaId = 'iddistrict';
            $lowerId= 'idelection_division';
            $dropDown= 'electionDivision';
            $division = ElectionDivision::query();
        }

        $items  = $division->where($areaId,$area)->where('status',1)->get();
        foreach ($items as $item){
            $fullAhouseCount_A = 0;
            $fullAvotersCount_A = 0;
            $fullAflavored_A = 0;
            $fullAparty_A = 0;
            $fullAopposite_A = 0;
            $fullAfloating_A = 0;
            $fullAoption1_A = 0;
            $fullAoption2_A = 0;
            $fullAoption3_A = 0;

            $fullAhouseCount_B = 0;
            $fullAvotersCount_B = 0;
            $fullAflavored_B = 0;
            $fullAparty_B = 0;
            $fullAopposite_B = 0;
            $fullAfloating_B = 0;
            $fullAoption1_B = 0;
            $fullAoption2_B = 0;
            $fullAoption3_B = 0;

            $villages  = Village::where($lowerId, $item->$lowerId)->where('status',1)->get();

            if($villages != null) {

                foreach ($villages as $village) {

                    $houseCount_A = 0;
                    $votersCount_A = 0;
                    $flavored_A = 0;
                    $party_A = 0;
                    $opposite_A = 0;
                    $floating_A = 0;
                    $option1_A = 0;
                    $option2_A = 0;
                    $option3_A = 0;

                    $houseCount_B = 0;
                    $votersCount_B = 0;
                    $flavored_B = 0;
                    $party_B = 0;
                    $opposite_B = 0;
                    $floating_B = 0;
                    $option1_B = 0;
                    $option2_B = 0;
                    $option3_B = 0;

                    //Firs round
                    $round1Canvassings = Canvassing::where('idcanvassing_type', $round1)->whereHas('village', function ($q) use ($village) {
                        $q->where('idvillage', $village->idvillage);
                    })->where('idoffice', Auth::user()->idoffice)->where('status', 3)->get();

                    if($round1Canvassings != null) {

                        foreach ($round1Canvassings as $round1Canvassing) {

                            //First Canvassing Details
                            $houseQuery_A = House::query();
                            $dynamicQuery_A = HouseDynamic::query();

                            $houses_A = $houseQuery_A->where('idoffice', Auth::user()->idoffice)->whereHas('houseDynamics',function ($q)use ($round1Canvassing){
                                $q->where('idcanvassing',$round1Canvassing->idcanvassing);
                            })->where('idvillage', $village->idvillage)->where('status', 1)->get();
                            if($houses_A != null) {
                                foreach ($houses_A as $house_A) {
                                    $eldersCount_A = 0;
                                    $youngCount_A = 0;
                                    $firstCount_A = 0;

                                    if ($voterCategory == 1) {
                                        $eldersCount_A = $house_A->elderVoters;
                                    } else if ($voterCategory == 2) {
                                        $youngCount_A = $house_A->youngVoters;
                                    } else if ($voterCategory == 3) {
                                        $youngCount_A = $house_A->firstVoters;
                                    } else {
                                        $eldersCount_A = $house_A->elderVoters;
                                        $youngCount_A = $house_A->youngVoters;
                                        $firstCount_A = $house_A->firstVoters;
                                    }

                                    $houseCount_A++;
                                    $votersCount_A += $eldersCount_A + $youngCount_A + $firstCount_A;

                                    $housesDynamics_A = $dynamicQuery_A->where('idcanvassing', $round1Canvassing->idcanvassing)->where('idhouse', $house_A->idhouse)->where('status', 1)->get();
                                    foreach ($housesDynamics_A as $housesDynamic_A) {

                                        if ($housesDynamic_A->idvoting_condition == 1) {
                                            $flavored_A++;
                                        } else if ($housesDynamic_A->idvoting_condition == 2) {
                                            $party_A++;
                                        } else if ($housesDynamic_A->idvoting_condition == 3) {
                                            $opposite_A++;
                                        } else if ($housesDynamic_A->idvoting_condition == 4) {
                                            $option1_A++;
                                        } else if ($housesDynamic_A->idvoting_condition == 5) {
                                            $option2_A++;
                                        } else if ($housesDynamic_A->idvoting_condition == 6) {
                                            $option3_A++;
                                        } else if ($housesDynamic_A->idvoting_condition == 7) {
                                            $floating_A++;
                                        }

                                    }
                                }
                            }
                            $fullAvotersCount_A += $votersCount_A;
                            $fullAhouseCount_A += $houseCount_A;
                            $fullAflavored_A += $flavored_A;
                            $fullAparty_A += $party_A;
                            $fullAopposite_A += $opposite_A;
                            $fullAoption1_A += $option1_A;
                            $fullAoption2_A += $option2_A;
                            $fullAoption3_A += $option3_A;
                            $fullAfloating_A += $floating_A;
                            //First Canvassing Details End


                        }
                    }
                    //Firs round end


                    //Second round
                    $round2Canvassings = Canvassing::where('idcanvassing_type', $round2)->whereHas('village', function ($q) use ($village) {
                        $q->where('idvillage', $village->idvillage);
                    })->where('idoffice', Auth::user()->idoffice)->where('status', 3)->get();

                    if($round2Canvassings != null) {
                        foreach ($round2Canvassings as $round2Canvassing) {

                            //Second Canvassing Details
                            $dynamicQuery_B = HouseDynamic::query();
                            $houseQuery_B = House::query();

                            $houses_B = $houseQuery_B->where('idoffice', Auth::user()->idoffice)->whereHas('houseDynamics',function ($q)use ($round2Canvassing){
                                $q->where('idcanvassing',$round2Canvassing->idcanvassing);
                            })->where('idvillage', $village->idvillage)->where('status', 1)->get();
                            if($houses_B != null) {
                                foreach ($houses_B as $house_B) {
                                    $eldersCount_B = 0;
                                    $youngCount_B = 0;
                                    $firstCount_B = 0;

                                    if ($voterCategory == 1) {
                                        $eldersCount_B = $house_B->elderVoters;
                                    } else if ($voterCategory == 2) {
                                        $youngCount_B = $house_B->youngVoters;
                                    } else if ($voterCategory == 3) {
                                        $youngCount_B = $house_B->firstVoters;
                                    } else {
                                        $eldersCount_B = $house_B->elderVoters;
                                        $youngCount_B = $house_B->youngVoters;
                                        $firstCount_B = $house_B->firstVoters;
                                    }

                                    $houseCount_B++;
                                    $votersCount_B += $eldersCount_B + $youngCount_B + $firstCount_B;

                                    $housesDynamics_B = $dynamicQuery_B->where('idcanvassing', $round2Canvassing->idcanvassing)->where('idhouse', $house_B->idhouse)->where('status', 1)->get();
                                    foreach ($housesDynamics_B as $housesDynamic_B) {

                                        if ($housesDynamic_B->idvoting_condition == 1) {
                                            $flavored_B++;
                                        } else if ($housesDynamic_B->idvoting_condition == 2) {
                                            $party_B++;
                                        } else if ($housesDynamic_B->idvoting_condition == 3) {
                                            $opposite_B++;
                                        } else if ($housesDynamic_B->idvoting_condition == 4) {
                                            $option1_B++;
                                        } else if ($housesDynamic_B->idvoting_condition == 5) {
                                            $option2_B++;
                                        } else if ($housesDynamic_B->idvoting_condition == 6) {
                                            $option3_B++;
                                        } else if ($housesDynamic_B->idvoting_condition == 7) {
                                            $floating_B++;
                                        }

                                    }
                                }
                            }
                            $fullAvotersCount_B += $votersCount_B;
                            $fullAhouseCount_B += $houseCount_B;
                            $fullAflavored_B += $flavored_B;
                            $fullAparty_B += $party_B;
                            $fullAopposite_B += $opposite_B;
                            $fullAoption1_B += $option1_B;
                            $fullAoption2_B += $option2_B;
                            $fullAoption3_B += $option3_B;
                            $fullAfloating_B += $floating_B;
                            //Second Canvassing Details End

                        }
                    }
                    //Second round end

                }
            }

            $item['id'] = $item->$lowerId;
            $item['dropDown'] = $dropDown;
            $item['name'] = $item->name_en;

            $item['totalVoters_A'] = $fullAvotersCount_A;
            $item['totalHouses_A'] = $fullAhouseCount_A;
            $item['flavored_A'] = $fullAflavored_A;
            $item['party_A'] = $fullAparty_A;
            $item['opposite_A'] = $fullAopposite_A;
            $item['option1_A'] = $fullAoption1_A;
            $item['option2_A'] = $fullAoption2_A;
            $item['option3_A'] = $fullAoption3_A;
            $item['floating_A'] = $fullAfloating_A;
            $item['percentage_A'] = $fullAvotersCount_A > 0 ? round(($fullAflavored_A/$fullAvotersCount_A)*100,1) : 0;

            $lastRowhouseCount_A += $fullAhouseCount_A;
            $lastRowvotersCount_A += $fullAvotersCount_A;
            $lastRowflavored_A += $fullAflavored_A;
            $lastRowparty_A += $fullAparty_A;
            $lastRowopposite_A += $fullAopposite_A;
            $lastRowfloating_A += $fullAfloating_A;
            $lastRowoption1_A += $fullAoption1_A;
            $lastRowoption2_A += $fullAoption2_A;
            $lastRowoption3_A += $fullAoption3_A;

            $item['totalVoters_B'] = $fullAvotersCount_B;
            $item['totalHouses_B'] = $fullAhouseCount_B;
            $item['flavored_B'] = $fullAflavored_B;
            $item['party_B'] = $fullAparty_B;
            $item['opposite_B'] = $fullAopposite_B;
            $item['option1_B'] = $fullAoption1_B;
            $item['option2_B'] = $fullAoption2_B;
            $item['option3_B'] = $fullAoption3_B;
            $item['floating_B'] = $fullAfloating_B;
            $item['percentage_B'] = $fullAvotersCount_B > 0 ? round(($fullAflavored_B/$fullAvotersCount_B)*100,1) : 0;

            $lastRowhouseCount_B += $fullAhouseCount_B;
            $lastRowvotersCount_B += $fullAvotersCount_B;
            $lastRowflavored_B += $fullAflavored_B;
            $lastRowparty_B += $fullAparty_B;
            $lastRowopposite_B += $fullAopposite_B;
            $lastRowfloating_B += $fullAfloating_B;
            $lastRowoption1_B += $fullAoption1_B;
            $lastRowoption2_B += $fullAoption2_B;
            $lastRowoption3_B += $fullAoption3_B;

        }

        $lastRow['totalVoters_A'] = $lastRowvotersCount_A;
        $lastRow['flavored_A'] = $lastRowflavored_A;
        $lastRow['floating_A'] = $lastRowfloating_A;
        $lastRow['opposite_A'] = $lastRowopposite_A;
        $lastRow['totalHouses_A'] = $lastRowhouseCount_A;
        $lastRow['party_A'] = $lastRowparty_A;
        $lastRow['option1_A'] = $lastRowoption1_A;
        $lastRow['option2_A'] = $lastRowoption2_A;
        $lastRow['option3_A'] = $lastRowoption3_A;
        $lastRow['percentage_A'] = $lastRowvotersCount_A > 0 ? round(($lastRowflavored_A/$lastRowvotersCount_A)*100,1) : 0;

        $lastRow['totalVoters_B'] = $lastRowvotersCount_B;
        $lastRow['flavored_B'] = $lastRowflavored_B;
        $lastRow['floating_B'] = $lastRowfloating_B;
        $lastRow['opposite_B'] = $lastRowopposite_B;
        $lastRow['totalHouses_B'] = $lastRowhouseCount_B;
        $lastRow['party_B'] = $lastRowparty_B;
        $lastRow['option1_B'] = $lastRowoption1_B;
        $lastRow['option2_B'] = $lastRowoption2_B;
        $lastRow['option3_B'] = $lastRowoption3_B;
        $lastRow['percentage_B'] = $lastRowvotersCount_B > 0 ? round(($lastRowflavored_B/$lastRowvotersCount_B)*100,1) : 0;

        return response()->json(['success' => $items,'lastRow'=>$lastRow]);
    }

}
