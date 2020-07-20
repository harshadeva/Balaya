<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\TaskController;
use App\VotersCount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiInitialSetupController extends Controller
{
    public function markVillage(Request $request){
        $validator = \Validator::make($request->all(), [
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ], [
            'lat.required' => 'Latitude should be provided!',
            'lat.numeric' => 'Latitude invalid!',
            'long.required' => 'Longitude should be provided!',
            'long.numeric' => 'Longitude invalid!',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }

//        $isConfirmed = VotersCount::where('idoffice',Auth::user()->idoffice)->where('idvillage',Auth::user()->agent->idvillage)->where('status',1)->first();

//        if($isConfirmed == null) {
            $voters = VotersCount::where('idoffice', Auth::user()->idoffice)->where('idvillage', Auth::user()->agent->idvillage)->where('status', 2)->first();
            if ($voters != null) {
                $voters->idUser = Auth::user()->idUser;
                $voters->lat = $request['lat'];
                $voters->long = $request['long'];
                $voters->update();
            } else {
                $voters = new VotersCount();
                $voters->idvillage = Auth::user()->agent->idvillage;
                $voters->idoffice = Auth::user()->idoffice;
                $voters->idUser = Auth::user()->idUser;
                $voters->lat = $request['lat'];
                $voters->long = $request['long'];
                $voters->total = 0;
                $voters->forecasting = 0;
                $voters->houses = 0;
                $voters->status = 2;
                $voters->save();
            }
            return response()->json(['success' => 'Location saved', 'statusCode' => 0]);

//        }else{
//            return response()->json(['error' => 'Village details cannot be edited after first canvassing end.', 'statusCode' => -99]);
//
//        }


    }

    public function storeVotersCount(Request $request){

        if (Auth::user()->iduser_role != 6) {
            return response()->json(['error' => 'You are not an agent', 'statusCode' => -99]);
        }
//        $isConfirmed = VotersCount::where('idoffice',Auth::user()->idoffice)->where('idvillage',Auth::user()->agent->idvillage)->where('status',1)->first();//check is first canvassing is started or not

//        if($isConfirmed == null) {
            $voters = VotersCount::where('idoffice', Auth::user()->idoffice)->where('idvillage', Auth::user()->agent->idvillage)->where('status', 2)->first();
            if ($voters != null) {
                $voters->total = round($request['total']);
                $voters->idUser = Auth::user()->idUser;
                $voters->forecasting = round($request['forecasting']);
                $voters->houses = round($request['noOfHouses']);
                $voters->save();
            } else {
                $voters = new VotersCount();
                $voters->idvillage = Auth::user()->agent->idvillage;
                $voters->idoffice = Auth::user()->idoffice;
                $voters->idUser = Auth::user()->idUser;
                $voters->total = round($request['total']);
                $voters->forecasting = round($request['forecasting']);
                $voters->houses = round($request['noOfHouses']);
                $voters->status = 2;
                $voters->save();
            }

            app(TaskController::class)->calAgentBudget(Auth::user(), round($request['total']));

            return response()->json(['success' => 'Value saved', 'statusCode' => 0]);
//        }
//        else{
//            return response()->json(['error' => 'Village details cannot be edited after first canvassing end.', 'statusCode' => -99]);
//
//        }

    }
}
