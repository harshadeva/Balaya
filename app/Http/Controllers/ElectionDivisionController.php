<?php

namespace App\Http\Controllers;

use App\ElectionDivision;
use App\GramasewaDivision;
use App\PollingBooth;
use App\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ElectionDivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('election_division.add')->with(['title'=>'Election Division']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByAuth()
    {
        $district  = Auth::user()->office->iddistrict;
        $electionDivisions = ElectionDivision::where('iddistrict',$district)->whereIn('status',[1,2])->orderBy('name_en')->get();
        return response()->json(['success'  =>$electionDivisions]);
    }

    public function getByDistrict(Request $request)
    {
        $id  = intval($request['id']);
        $result = ElectionDivision::where('iddistrict',$id)->latest()->where('status',1)->get();
        return response()->json(['success'  => $result]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'electionDivision' => 'required|max:100',
            'electionDivision_si' => 'required|max:100',
            'electionDivision_ta' => 'required|max:100'

        ], [
            'electionDivision.required' => 'Election division should be provided!',
            'electionDivision_si.required' => 'Election division (Sinhala) should be provided!',
            'electionDivision_ta.required' => 'Election division (Tamil) should be provided!',
            'electionDivision.max' => 'Election division should be less than 100 characters long!',
            'electionDivision_si.max' => 'Election division (Sinhala) should be less than 100 characters long!',
            'electionDivision_ta.max' => 'Election division (Tamil) should be less than 100 characters long!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        //validation end

        $division = new ElectionDivision();
        $division->iddistrict = Auth::user()->office->iddistrict;
        $division->name_en = strtoupper($request['electionDivision']);
        $division->name_si = $request['electionDivision_si'];
        $division->name_ta = $request['electionDivision_ta'];
        $division->status = 2;
        $division->idUser = Auth::user()->idUser;
        $division->save();
        return response()->json(['success' => 'Election division saved']);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'updateId' => 'required',
            'electionDivision' => 'required|max:100',
            'electionDivision_si' => 'required|max:100',
            'electionDivision_ta' => 'required|max:100'

        ], [
            'updateId.required' => 'Update process has failed!',
            'electionDivision.required' => 'Election division should be provided!',
            'electionDivision_si.required' => 'Election division (Sinhala) should be provided!',
            'electionDivision_ta.required' => 'Election division (Tamil) should be provided!',
            'electionDivision.max' => 'Election division should be less than 100 characters long!',
            'electionDivision_si.max' => 'Election division (Sinhala) should be less than 100 characters long!',
            'electionDivision_ta.max' => 'Election division (Tamil) should be less than 100 characters long!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $division = ElectionDivision::find($request['updateId']);
        if($division->status != 2){
            return response()->json(['errors' => ['error'=>'Sorry! Election divisions are not allowed to update after confirmation.']]);
        }

        //validation end

        // save in election division table
        $division->name_en = strtoupper($request['electionDivision']);
        $division->name_si = $request['electionDivision_si'];
        $division->name_ta = $request['electionDivision_ta'];
        $division->idUser = Auth::user()->idUser;
        $division->save();
        // save in election division table end


        return response()->json(['success' => 'Election division updated']);
    }

    public function confirm(Request $request){
        $electionDivisions = ElectionDivision::where('idUser',Auth::user()->idUser)->where('status',2)->get();
        $electionDivisions->each(function ($item,$key){
            $item->status = 1;
            $item->save();
        });
        return response()->json(['success' => 'Election divisions confirmed']);
    }

    public function deleteRecord(Request $request){
        $id  = $request['id'];
        $record  =  ElectionDivision::find(intval($id));
        if($record->status == 2){


            $confirmedVillage = Village::where('idelection_division',$id)->where('status', '!=' ,2)->count();
            if($confirmedVillage > 0){
                return response()->json(['errors' => ['error'=>'This division has some confirmed villages.']]);
            }
            $confirmedGramasewa  = GramasewaDivision::where('idelection_division',$id)->where('status','!=',2)->count();
            if($confirmedGramasewa > 0){
                return response()->json(['errors' => ['error'=>'This division has some confirmed gramasewa divisions.']]);
            }
            $confirmedBooths  = PollingBooth::where('idelection_division',$id)->where('status','!=',2)->count();
            if($confirmedBooths > 0){
                return response()->json(['errors' => ['error'=>'This division has some confirmed member divisions.']]);
            }

            Village::where('idelection_division',$id)->delete();
            GramasewaDivision::where('idelection_division',$id)->delete();
            PollingBooth::where('idelection_division',$id)->delete();
            $record->delete();

            return response()->json(['success' => 'Record deleted']);
        }
        return response()->json(['errors' => ['error'=>'You are not able to delete this record.']]);

    }

}
