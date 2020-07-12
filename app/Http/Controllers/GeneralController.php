<?php

namespace App\Http\Controllers;

use App\Canvassing;
use App\Http\Controllers\Api\ApiCanvassingController;
use App\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneralController extends Controller
{
    public function index(Request $request){
        $os = $request['os'];

        if($os == null){
            return response()->json(['error' => 'Please provide OS!', 'statusCode' => -99]);
        }

        $response['version'] = $this->getAppVersion($os);
        $response['canvassingOngoing'] =  app(ApiCanvassingController::class)->onGoingCanvassingExist(Auth::user()->idUser);

        return response()->json(['success' => $response, 'statusCode' => 0]);
    }

    public function getAppVersion($os){

        $version = SystemSetting::where('status',1)->first()->$os;
        if($version == null){
            return response()->json(['error' => 'Invalid!', 'statusCode' => -99]);
        }
        return $version;
    }

    public function appVersion(Request $request){

        $setting = SystemSetting::where('status',1)->first();

        return view('setting.app_version')->with(['title'=>'App Version','setting'=>$setting]);
    }

    public function storeAppVersion(Request $request){
        $validationMessages = [
            'android.required' => 'Android version should be provided!',
            'ios.required' => 'Ios version should be provided!',
        ];

        $validator = \Validator::make($request->all(), [
            'android' => 'required',
            'ios' => 'required',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $setting = SystemSetting::first();
        if($setting != null){
            if($setting->android != $request['android']){
                $setting->android_date = now();
            }
            if($setting->ios != $request['ios']){
                $setting->ios_date = now();
            }
            $setting->android = $request['android'];
            $setting->ios = $request['ios'];
            $setting->status = 1;
            $setting->save();
        }
        else{
            $setting = new SystemSetting();
            $setting->android = $request['android'];
            $setting->ios = $request['ios'];
            $setting->android_date = now();
            $setting->ios_date = now();
            $setting->statsu = 1;
            $setting->save();
        }
        return response()->json(['success' => 'success']);

    }
}
