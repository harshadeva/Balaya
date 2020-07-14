<?php

namespace App\Http\Controllers;

use App\Agent;
use App\ElectionDivision;
use App\GramasewaDivision;
use App\PollingBooth;
use App\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollingBoothController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $electionDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status','>=',1)->get();
        return view('polling_booth.add')->with(['title'=>'Member Division','electionDivisions'=>$electionDivisions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByAuth(Request $request)
    {
        $district  = intval(Auth::user()->office->iddistrict);
        $query  = PollingBooth::query();
        if($request['id'] != null){
            $query  = $query->where('idelection_division',$request['id']);
        }
        $pollingBooth = $query->with(['electionDivision'])->where('iddistrict',$district)->whereIn('status',[1,2])->orderBy('name_en')->get();
        return response()->json(['success'  => $pollingBooth]);
    }


    public function getByElectionDivision(Request $request)
    {
        $id  = intval($request['id']);
        $result = PollingBooth::where('idelection_division',$id)->latest()->where('status',1)->get();
        return response()->json(['success'  => $result]);
    }

    public function getByElectionDivisions(Request $request)
    {
        $ids  = $request['id'];
        $merged  = collect();
        if(!empty($ids)){
            foreach ($ids as $id){
                $next = PollingBooth::where('idelection_division',$id)->latest()->where('status',1)->get();
                if($next != null){
                    $merged = $merged->merge($next);
                }
            }
            return response()->json(['success'  => $merged]);

        }
        else{
            return response()->json(['errors'  => ['error'=>'Invalid parameter']]);
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
            'electionDivision' => 'required|exists:election_division,idelection_division',
            'pollingBooth' => 'required|max:100',
            'pollingBooth_si' => 'required|max:100',
            'pollingBooth_ta' => 'required|max:100'

        ], [
            'electionDivision.required' => 'Election Division should be provided!',
            'electionDivision.exists' => 'Election Division invalid!',
            'pollingBooth.required' => 'Member division should be provided!',
            'pollingBooth_si.required' => 'Member division (Sinhala) should be provided!',
            'pollingBooth_ta.required' => 'Member division (Tamil) should be provided!',
            'pollingBooth.max' => 'Member division should be less than 100 characters long!',
            'pollingBooth_si.max' => 'Member division (Sinhala) should be less than 100 characters long!',
            'pollingBooth_ta.max' => 'Member division (Tamil) should be less than 100 characters long!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        //validation end

        $booth = new PollingBooth();
        $booth->idelection_division = $request['electionDivision'];
        $booth->iddistrict = ElectionDivision::find(intval($request['electionDivision']))->iddistrict;
        $booth->name_en = $request['pollingBooth'];
        $booth->name_si = $request['pollingBooth_si'];
        $booth->name_ta = $request['pollingBooth_ta'];
        $booth->status = 2;
        $booth->idUser = Auth::user()->idUser;
        $booth->save();
        return response()->json(['success' => 'Member division saved']);
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
            'electionDivision' => 'required|exists:election_division,idelection_division',
            'pollingBooth' => 'required|max:100',
            'pollingBooth_si' => 'required|max:100',
            'pollingBooth_ta' => 'required|max:100'

        ], [
            'updateId.required' => 'Update process has failed!',
            'electionDivision.required' => 'Election Division should be provided!',
            'electionDivision.exists' => 'Election Division invalid!',
            'pollingBooth.required' => 'Member division should be provided!',
            'pollingBooth_si.required' => 'Member division (Sinhala) should be provided!',
            'pollingBooth_ta.required' => 'Member division (Tamil) should be provided!',
            'pollingBooth.max' => 'Member division should be less than 100 characters long!',
            'pollingBooth_si.max' => 'Member division (Sinhala) should be less than 100 characters long!',
            'pollingBooth_ta.max' => 'Member division (Tamil) should be less than 100 characters long!',

        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $parentChanged = false;
        $booth = PollingBooth::find($request['updateId']);

        if($booth->status != 2){
            return response()->json(['errors' => ['error'=>'Sorry! Polling booths are not allowed to update after confirmation.']]);
        }

        if($booth->idelection_division != $request['electionDivision']){
            $users = Agent::where('idpolling_booth',$request['updateId'])->first();
            if($users != null) {
                return response()->json(['errors' => ['electionDivision'=>'Election division can not be changed!']]);
            }
            $booth->idelection_division = $request['electionDivision'];
            $parentChanged = true;
        }

        //validation end
        $booth->name_en = $request['pollingBooth'];
        $booth->name_si = $request['pollingBooth_si'];
        $booth->name_ta = $request['pollingBooth_ta'];
        $booth->idUser = Auth::user()->idUser;
        $booth->save();

        //save in relation table
        if($parentChanged) {
            $gramasewaDivisions = GramasewaDivision::where('idpolling_booth', $booth->idpolling_booth)->get();
            if ($gramasewaDivisions != null) {
                $gramasewaDivisions->each(function ($item, $key) use ($request) {
                    $item->idelection_division = $request['electionDivision'];
                    $item->idUser = Auth::user()->idUser;
                    $item->save();

                    $villages = Village::where('idgramasewa_division', $item->idgramasewa_division)->get();
                    if ($villages != null) {
                        $villages->each(function ($item1, $key1) use ($item) {
                            $item1->idpolling_booth = $item->idpolling_booth;
                            $item1->idelection_division = $item->idelection_division;
                            $item1->idUser = Auth::user()->idUser;
                            $item1->save();
                        });
                    }
                });
            }
        }
        //save in relation table end

        return response()->json(['success' => 'Member division updated']);
    }

    public function confirm(Request $request){
        $pollingBooths = PollingBooth::where('idUser',Auth::user()->idUser)->where('status',2)->get();
        $pollingBooths->each(function ($item,$key){
            $item->status = 1;
            $item->save();
        });
        return response()->json(['success' => 'Member divisions confirmed']);
    }

    public function deleteRecord(Request $request){
        $id  = $request['id'];
        $record  =  PollingBooth::find(intval($id));
        if($record->status == 2){

            $confirmedVillage = Village::where('idpolling_booth',$id)->where('status', '!=' ,2)->count();
            if($confirmedVillage > 0){
                return response()->json(['errors' => ['error'=>'This division has some confirmed villages.']]);
            }
            $confirmedGramasewa  = GramasewaDivision::where('idpolling_booth',$id)->where('status','!=',2)->count();
            if($confirmedGramasewa > 0){
                return response()->json(['errors' => ['error'=>'This division has some confirmed gramasewa divisions.']]);
            }

            Village::where('idpolling_booth',$id)->delete();
            GramasewaDivision::where('idpolling_booth',$id)->delete();

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
