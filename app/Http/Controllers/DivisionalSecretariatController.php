<?php

namespace App\Http\Controllers;

use App\DivisionalSecretariat;
use App\GramasewaDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisionalSecretariatController extends Controller
{
    /**
     * Display insert page
     */
    public function index()
    {
        return view('divisional_secretariat.add')->with(['title'=>'Divisional Secretariat']);
    }


    /**
     * Store a newly created resource in database.
     *
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'secretariat' => 'required|max:100',
            'secretariat_si' => 'required|max:100',
            'secretariat_ta' => 'required|max:100'

        ], [
            'secretariat.required' => 'Divisional Secretariat should be provided!',
            'secretariat_si.required' => 'Divisional Secretariat (Sinhala) should be provided!',
            'secretariat_ta.required' => 'Divisional Secretariat (Tamil) should be provided!',
            'secretariat.max' => 'Divisional Secretariat should be less than 100 characters long!',
            'secretariat_si.max' => 'Divisional Secretariat (Sinhala) should be less than 100 characters long!',
            'secretariat_ta.max' => 'Divisional Secretariat (Tamil) should be less than 100 characters long!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        //validation end

        $division = new DivisionalSecretariat();
        $division->iddistrict = Auth::user()->office->iddistrict;
        $division->name_en = strtoupper($request['secretariat']);
        $division->name_si = $request['secretariat_si'];
        $division->name_ta = $request['secretariat_ta'];
        $division->status = 2;
        $division->idUser = Auth::user()->idUser;
        $division->save();
        return response()->json(['success' => 'Divisional Secretariat saved']);

    }

    public function getByAuth()
    {
        $district  = Auth::user()->office->iddistrict;
        $divisionalSecretariat = DivisionalSecretariat::where('iddistrict',$district)->whereIn('status',[1,2])->orderBy('name_en')->get();
        return response()->json(['success'  =>$divisionalSecretariat]);
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
            'divisionalSecretariat' => 'required|max:100',
            'divisionalSecretariat_si' => 'required|max:100',
            'divisionalSecretariat_ta' => 'required|max:100'

        ], [
            'updateId.required' => 'Update process has failed!',
            'divisionalSecretariat.required' => 'Divisional Secretariat should be provided!',
            'divisionalSecretariat_si.required' => 'Divisional Secretariat (Sinhala) should be provided!',
            'divisionalSecretariat_ta.required' => 'Divisional Secretariat (Tamil) should be provided!',
            'divisionalSecretariat.max' => 'Divisional Secretariat should be less than 100 characters long!',
            'divisionalSecretariat_si.max' => 'Divisional Secretariat (Sinhala) should be less than 100 characters long!',
            'divisionalSecretariat_ta.max' => 'Divisional Secretariat (Tamil) should be less than 100 characters long!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $division = DivisionalSecretariat::find($request['updateId']);
        if($division->status != 2){
            return response()->json(['errors' => ['error'=>'Sorry! Divisional Secretariats are not allowed to update after confirmation.']]);
        }

        //validation end

        // save in divisional secretariat table
        $division->name_en = strtoupper($request['divisionalSecretariat']);
        $division->name_si = $request['divisionalSecretariat_si'];
        $division->name_ta = $request['divisionalSecretariat_ta'];
        $division->idUser = Auth::user()->idUser;
        $division->save();
        // save in divisional secretariat table end
        return response()->json(['success' => 'Divisional Secretariat updated']);
    }

    public function deleteRecord(Request $request){
        $id  = $request['id'];
        $record  =  DivisionalSecretariat::find(intval($id));
        if($record->status == 2){
            $record->delete();
            return response()->json(['success' => 'Record deleted']);
        }
        return response()->json(['errors' => ['error'=>'You are not able to delete this record.']]);

    }

    public function view(Request $request)
    {
        $secretariatName = $request['secretariatName'];
        $endDate = $request['end'];
        $startDate = $request['start'];

        $query = DivisionalSecretariat::query();
        if (!empty($secretariatName)) {
            $query = $query->where('name_en', 'like', '%' . $secretariatName . '%');
        }

        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end'] . ' +1 day'));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $secretariats = $query->where('iddistrict',Auth::user()->office->iddistrict)->latest()->where('status',1)->paginate(10);

        return view('divisional_secretariat.view')->with(['title'=>'Divisional Secretariat View','secretariats'=>$secretariats]);
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
                $gramasewa->iddivisional_secretariat = $id;
                $gramasewa->save();
            }
        }
        else{
            return response()->json(['errors' => ['error'=>'Please select one or more record(s).']]);
        }

        $count = GramasewaDivision::where('iddivisional_secretariat',$id)->where('status',1)->count();
        return response()->json(['success' => 'Gramasewa division assigned.','count'=>$count]);

    }

    public function confirm(Request $request){
        $record = DivisionalSecretariat::where('idUser',Auth::user()->idUser)->where('status',2)->get();
        $record->each(function ($item,$key){
            $item->status = 1;
            $item->save();
        });
        return response()->json(['success' => 'Divisional secretariats confirmed']);
    }
}
