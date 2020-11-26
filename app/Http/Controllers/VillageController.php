<?php

namespace App\Http\Controllers;

use App\Agent;
use App\GramasewaDivision;
use App\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VillageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gramasewaDivisions = GramasewaDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status','>=',1)->get();
        return view('village.add')->with(['title'=>'Village','gramasewaDivisions'=>$gramasewaDivisions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByAuth(Request $request)
    {
        $district  = intval(Auth::user()->office->iddistrict);
        $query = Village::query();
        if($request['id'] != null){
            $query  = $query->where('idgramasewa_division',$request['id']);
        }
        $villages = $query->with(['gramasewaDivision'])->where('iddistrict',$district)->whereIn('status',[1,2])->orderBy('name_en')->get();
        return response()->json(['success'  => $villages]);
    }

    public function getByGramasewaDivision(Request $request)
    {
        $id  = intval($request['id']);
        if($id != null) {
            $result = Village::where('idgramasewa_division', $id)->latest()->where('status', 1)->get();
            return response()->json(['success' => $result]);
        }
        else{
            return response()->json(['errors' => ['error'=>'Please provide selected value.']]);

        }
    }

    public function getByGramasewaDivisions(Request $request)
    {
        $ids  = $request['id'];
        $merged  = collect();
        if(!empty($ids)){
            foreach ($ids as $id){
                $next = Village::where('idgramasewa_division',$id)->latest()->where('status',1)->get();
                if($next != null){
                    $merged = $merged->merge($next);
                }
            }
            return response()->json(['success'  => $merged]);

        }

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
            'gramasewaDivision' => 'required|exists:gramasewa_division,idgramasewa_division',
            'village' => 'required|max:100',
            'village_si' => 'required|max:100',
            'village_ta' => 'required|max:100'

        ], [
            'gramasewaDivision.required' => 'Gramasewa Division should be provided!',
            'gramasewaDivision.exists' => 'Gramasewa Division invalid!',
            'village.required' => 'Village should be provided!',
            'village_si.required' => 'Village (Sinhala) should be provided!',
            'village_ta.required' => 'Village (Tamil) should be provided!',
            'village.max' => 'Village should be less than 100 characters long!',
            'village_si.max' => 'Village (Sinhala) should be less than 100 characters long!',
            'village_ta.max' => 'Village (Tamil) should be less than 100 characters long!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        //validation end

        $division = new Village();
        $division->idgramasewa_division = $request['gramasewaDivision'];
        $division->idpolling_booth = GramasewaDivision::find(intval($request['gramasewaDivision']))->idpolling_booth;
        $division->idelection_division = GramasewaDivision::find(intval($request['gramasewaDivision']))->idelection_division;
        $division->iddistrict = GramasewaDivision::find(intval($request['gramasewaDivision']))->iddistrict;
        $division->name_en = $request['village'];
        $division->name_si = $request['village_si'];
        $division->name_ta = $request['village_ta'];
        $division->status = 2;
        $division->idUser = Auth::user()->idUser;
        $division->save();
        return response()->json(['success' => 'Village saved']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            'gramasewaDivision' => 'required|exists:gramasewa_division,idgramasewa_division',
            'village' => 'required|max:100',
            'village_si' => 'required|max:100',
            'village_ta' => 'required|max:100'

        ], [
            'updateId.required' => 'Update process has failed!',
            'gramasewaDivision.required' => 'Gramasewa Division should be provided!',
            'gramasewaDivision.exists' => 'Gramasewa Division invalid!',
            'village.required' => 'Village should be provided!',
            'village_si.required' => 'Village (Sinhala) should be provided!',
            'village_ta.required' => 'Village (Tamil) should be provided!',
            'village.max' => 'Village should be less than 100 characters long!',
            'village_si.max' => 'Village (Sinhala) should be less than 100 characters long!',
            'village_ta.max' => 'Village (Tamil) should be less than 100 characters long!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $village = Village::find($request['updateId']);

        if($village->status != 2){
            return response()->json(['errors' => ['error'=>'Sorry! Villages are not allowed to update after confirmation.']]);
        }

        if($village->idgramasewa_division != $request['gramasewaDivision']){
            $users = Agent::where('idvillage',$request['updateId'])->first();
            if($users != null) {
                return response()->json(['errors' => ['pollingBooth'=>'Gramasewa division can not be changed!']]);
            }
            $village->idgramasewa_division = $request['gramasewaDivision'];
            $village->idpolling_booth = GramasewaDivision::find(intval($request['gramasewaDivision']))->idpolling_booth;
            $village->idelection_division = GramasewaDivision::find(intval($request['gramasewaDivision']))->idelection_division;
        }
        //validation end

        $village->name_en = $request['village'];
        $village->name_si = $request['village_si'];
        $village->name_ta = $request['village_ta'];
        $village->idUser = Auth::user()->idUser;
        $village->save();
        return response()->json(['success' => 'Village updated']);
    }

    public function confirm(Request $request){
        $gramasewaDivisions = Village::where('idUser',Auth::user()->idUser)->where('status',2)->get();
        $gramasewaDivisions->each(function ($item,$key){
            $item->status = 1;
            $item->save();
        });
        return response()->json(['success' => 'Village confirmed']);
    }

    public function deleteRecord(Request $request){
        $id  = $request['id'];
        $record  =  Village::find(intval($id));
        if($record->status == 2){

            $record->delete();
            return response()->json(['success' => 'Record deleted']);
        }
        return response()->json(['errors' => ['error'=>'You are not able to delete this record.']]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
