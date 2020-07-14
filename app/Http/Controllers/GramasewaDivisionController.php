<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Council;
use App\CouncilTypes;
use App\DivisionalSecretariat;
use App\GramasewaDivision;
use App\PollingBooth;
use App\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GramasewaDivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pollingBooths = PollingBooth::where('iddistrict',Auth::user()->office->iddistrict)->where('status','>=',1)->get();
        return view('gramasewa_division.add')->with(['title'=>'Gramasewa Division','pollingBooths'=>$pollingBooths]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByAuth(Request $request)
    {
        $district  = intval(Auth::user()->office->iddistrict);
        $query = GramasewaDivision::query();
        if($request['id'] != null){
            $query  = $query->where('idpolling_booth',$request['id']);
        }

        $gramasewaDivisions = $query->with(['pollingBooth'])->where('iddistrict',$district)->whereIn('status',[1,2])->orderBy('name_en')->get();
        return response()->json(['success'  => $gramasewaDivisions]);
    }

    public function getByPollingBooth(Request $request)
    {
        $id  = intval($request['id']);
        $result = GramasewaDivision::where('idpolling_booth',$id)->latest()->where('status',1)->get();
        return response()->json(['success'  => $result]);
    }

    public function getByPollingBooths(Request $request)
    {
        $ids  = $request['id'];
        $merged  = collect();
        if(!empty($ids)){
            foreach ($ids as $id){
                $next = GramasewaDivision::where('idpolling_booth',$id)->latest()->where('status',1)->get();
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
            'pollingBooth' => 'required|exists:polling_booth,idpolling_booth',
            'gramasewaDivision' => 'required|max:100',
            'gramasewaDivision_si' => 'required|max:100',
            'gramasewaDivision_ta' => 'required|max:100',

        ], [
            'pollingBooth.required' => 'Member division should be provided!',
            'pollingBooth.exists' => 'Member division invalid!',
            'gramasewaDivision.required' => 'Gramasewa Division should be provided!',
            'gramasewaDivision_si.required' => 'Gramasewa Division (Sinhala) should be provided!',
            'gramasewaDivision_ta.required' => 'Gramasewa Division (Tamil) should be provided!',
            'gramasewaDivision.max' => 'Gramasewa Division should be less than 100 characters long!',
            'gramasewaDivision_si.max' => 'Gramasewa Division (Sinhala) should be less than 100 characters long!',
            'gramasewaDivision_ta.max' => 'Gramasewa Division (Tamil) should be less than 100 characters long!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        //validation end

        $division = new GramasewaDivision();
        $division->idpolling_booth = $request['pollingBooth'];
        $division->idelection_division = PollingBooth::find(intval($request['pollingBooth']))->idelection_division;
        $division->iddistrict = PollingBooth::find(intval($request['pollingBooth']))->iddistrict;
        $division->name_en = $request['gramasewaDivision'];
        $division->name_si = $request['gramasewaDivision_si'];
        $division->name_ta = $request['gramasewaDivision_ta'];
        $division->status = 2;
        $division->idUser = Auth::user()->idUser;
        $division->save();
        return response()->json(['success' => 'Gramasewa Division saved']);
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
            'pollingBooth' => 'required|exists:polling_booth,idpolling_booth',
            'gramasewaDivision' => 'required|max:100',
            'gramasewaDivision_si' => 'required|max:100',
            'gramasewaDivision_ta' => 'required|max:100'

        ], [
            'updateId.required' => 'Update process has failed!',
            'pollingBooth.required' => 'Member division should be provided!',
            'pollingBooth.exists' => 'Member division invalid!',
            'gramasewaDivision.required' => 'Gramasewa Division should be provided!',
            'gramasewaDivision_si.required' => 'Gramasewa Division (Sinhala) should be provided!',
            'gramasewaDivision_ta.required' => 'Gramasewa Division (Tamil) should be provided!',
            'gramasewaDivision.max' => 'Gramasewa Division should be less than 100 characters long!',
            'gramasewaDivision_si.max' => 'Gramasewa Division (Sinhala) should be less than 100 characters long!',
            'gramasewaDivision_ta.max' => 'Gramasewa Division (Tamil) should be less than 100 characters long!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $parentChanged = false;
        $division = GramasewaDivision::find($request['updateId']);

        if($division->status != 2){
            return response()->json(['errors' => ['error'=>'Sorry! Gramasewa divisions are not allowed to update after confirmation.']]);
        }

        if($division->idpolling_booth != $request['pollingBooth']){
            $users = Agent::where('idgramasewa_division',$request['updateId'])->first();
            if($users != null) {
                return response()->json(['errors' => ['pollingBooth'=>'Polling booth can not be changed!']]);
            }
            $division->idpolling_booth = $request['pollingBooth'];
            $division->idelection_division = PollingBooth::find(intval($request['pollingBooth']))->idelection_division;
            $parentChanged = true;
        }

        //validation end
        $division->name_en = $request['gramasewaDivision'];
        $division->name_si = $request['gramasewaDivision_si'];
        $division->name_ta = $request['gramasewaDivision_ta'];
        $division->idUser = Auth::user()->idUser;
        $division->save();

        //save in relation table
        if($parentChanged) {
            $villages = Village::where('idgramasewa_division', $division->idgramasewa_division)->get();
            if ($villages != null) {
                $villages->each(function ($item, $key) use ($division) {
                    $item->idpolling_booth = $division->idpolling_booth;
                    $item->idelection_division = $division->idelection_division;
                    $item->idUser = Auth::user()->idUser;
                    $item->save();
                });
            }
        }
        return response()->json(['success' => 'Gramasewa Division updated']);
    }

    public function confirm(Request $request){
        $gramasewaDivisions = GramasewaDivision::where('idUser',Auth::user()->idUser)->where('status',2)->get();
        $gramasewaDivisions->each(function ($item,$key){
            $item->status = 1;
            $item->save();
        });
        return response()->json(['success' => 'Gramasewa divisions confirmed']);
    }

    public function deleteRecord(Request $request){
        $id  = $request['id'];
        $record  =  GramasewaDivision::find(intval($id));
        if($record->status == 2){

            $confirmedVillage = Village::where('idgramasewa_division',$id)->where('status', '!=' ,2)->count();
            if($confirmedVillage > 0){
                return response()->json(['errors' => ['error'=>'This division has some confirmed villages.']]);
            }

            Village::where('idgramasewa_division',$id)->delete();

            $record->delete();

            return response()->json(['success' => 'Record deleted']);
        }
        return response()->json(['errors' => ['error'=>'You are not able to delete this record.']]);

    }

    public function getBySecretariat(Request $request){
        $secretariat = $request['id'];
        return response()->json(['success' => GramasewaDivision::with(['pollingBooth','pollingBooth.electionDivision'])->where('iddivisional_secretariat',$secretariat)->where('status',1)->get()]);
    }

    public function getUnAssigned(Request $request){
        return response()->json(['success' => GramasewaDivision::with(['pollingBooth','pollingBooth.electionDivision'])->where('iddivisional_secretariat',null)->where('iddistrict',Auth::user()->office->iddistrict)->where('status',1)->get()]);
    }

    public function getByCouncil(Request $request){
        $council = $request['id'];
        return response()->json(['success' => GramasewaDivision::with(['pollingBooth','pollingBooth.electionDivision'])->where('idcouncil',$council)->where('status',1)->get()]);
    }

    public function getUnCouncilled(Request $request){
        return response()->json(['success' => GramasewaDivision::with(['pollingBooth','pollingBooth.electionDivision'])->where('idcouncil',null)->where('status',1)->get()]);
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
