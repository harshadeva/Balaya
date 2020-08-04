<?php

namespace App\Http\Controllers;

use App\Council;
use App\CouncilTypes;
use App\GramasewaDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouncilController extends Controller
{
    /**
     * Display insert page
     */
    public function index()
    {
        $types   = CouncilTypes::where('status',1)->get();
        return view('council.add')->with(['title'=>'Council','types'=>$types]);
    }

    /**
     * Store a newly created resource in database.
     *
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'council' => 'required|max:100',
            'council_si' => 'required|max:100',
            'council_ta' => 'required|max:100',
            'councilType' => 'required'

        ], [
            'council.required' => 'Council should be provided!',
            'council_si.required' => 'Council (Sinhala) should be provided!',
            'council_ta.required' => 'Council (Tamil) should be provided!',
            'councilType.required' => 'Council type should be provided!',
            'council.max' => 'Council should be less than 100 characters long!',
            'council_si.max' => 'Council (Sinhala) should be less than 100 characters long!',
            'council_ta.max' => 'Council (Tamil) should be less than 100 characters long!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        //validation end

        $division = new Council();
        $division->iddistrict = Auth::user()->office->iddistrict;
        $division->idcouncil_types = strtoupper($request['councilType']);
        $division->name_en = strtoupper($request['council']);
        $division->name_si = $request['council_si'];
        $division->name_ta = $request['council_ta'];
        $division->status = 2;
        $division->idUser = Auth::user()->idUser;
        $division->save();
        return response()->json(['success' => 'Council saved']);

    }


    public function getByAuth(Request $request)
    {
        $district  = Auth::user()->office->iddistrict;
        $query  = Council::query();
        if($request['councilType'] != null){
            $query  = $query->where('idcouncil_types',$request['councilType']);
        }
        $council = $query->with(['councilType'])->where('iddistrict',$district)->whereIn('status',[1,2])->orderBy('name_en')->get();
        return response()->json(['success'  =>$council]);
    }

    public function deleteRecord(Request $request){
        $id  = $request['id'];
        $record  =  Council::find(intval($id));
        if($record->status == 2){
            $record->delete();
            return response()->json(['success' => 'Record deleted']);
        }
        return response()->json(['errors' => ['error'=>'You are not able to delete this record.']]);

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
            'council' => 'required|max:100',
            'council_si' => 'required|max:100',
            'council_ta' => 'required|max:100',
            'councilType' => 'required'
        ], [
            'councilType.required' => 'Council type should be provided!',
            'updateId.required' => 'Update process has failed!',
            'council.required' => 'Council should be provided!',
            'council_si.required' => 'Council (Sinhala) should be provided!',
            'council_ta.required' => 'Council (Tamil) should be provided!',
            'council.max' => 'Council should be less than 100 characters long!',
            'council_si.max' => 'Council (Sinhala) should be less than 100 characters long!',
            'council_ta.max' => 'Council (Tamil) should be less than 100 characters long!',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $council = Council::find($request['updateId']);
        if($council->status != 2){
            return response()->json(['errors' => ['error'=>'Sorry! Councils are not allowed to update after confirmation.']]);
        }

        //validation end

        // save in divisional secretariat table
        $council->idcouncil_types = strtoupper($request['councilType']);
        $council->name_en = strtoupper($request['council']);
        $council->name_si = $request['council_si'];
        $council->name_ta = $request['council_ta'];
        $council->idUser = Auth::user()->idUser;
        $council->save();
        // save in divisional secretariat table end


        return response()->json(['success' => 'Council updated']);
    }


    public function confirm(Request $request){
        $record = Council::where('idUser',Auth::user()->idUser)->where('status',2)->get();
        $record->each(function ($item,$key){
            $item->status = 1;
            $item->save();
        });
        return response()->json(['success' => 'Councils confirmed']);
    }

    public function view(Request $request)
    {
        $councilName = $request['councilName'];
        $endDate = $request['end'];
        $startDate = $request['start'];

        $query = Council::query();
        if (!empty($councilName)) {
            $query = $query->where('name_en', 'like', '%' . $councilName . '%');
        }

        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end'] . ' +1 day'));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $councils = $query->where('iddistrict',Auth::user()->office->iddistrict)->latest()->where('status',1)->paginate(10);

        return view('council.view')->with(['title'=>'Council View','councils'=>$councils]);
    }

    public function assignDivisions(Request $request){
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'divisionsArray' => 'required'

        ], [
            'id.required' => 'Process failed!.Please contact administrator',
            'divisionsArray.required' => 'Process failed!.Please contact administrator',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $id = $request['id'];
        $array = $request['divisionsArray'];

        if(count($array) > 0){
            foreach ($array as $item){
                $gramasewa = GramasewaDivision::find(intval($item));
                $gramasewa->idcouncil = $id;
                $gramasewa->save();
            }
        }
        else{
            return response()->json(['errors' => ['error'=>'Please select one or more record(s).']]);
        }

        $count = GramasewaDivision::where('idcouncil',$id)->where('status',1)->count();
        return response()->json(['success' => 'Gramasewa division assigned.','count'=>$count]);

    }
}
