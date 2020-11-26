<?php

namespace App\Http\Controllers\APi;

use App\Village;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiVillageController extends Controller
{
    public function getByGramasewaDivision(Request $request)
    {
        $apiLang = $request['lang'];
        $fallBack = 'name_en';
        if ($apiLang == 'si') {
            $lang = 'name_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'name_ta';
        } else {
            $lang = 'name_en';
        }
        $id  = intval($request['id']);
        if($id != null) {
            $results = Village::where('idgramasewa_division', $id)->latest()->select(['idvillage',$lang,$fallBack])->where('status', 1)->get();
            foreach ($results as $result) {

                $result['label'] = $result[$lang] != null ? $result[$lang] : $result[$fallBack];
                $result['id'] = $result['idvillage'];
                unset($result->idvillage);
                unset($result->name_en);
                unset($result->name_si);
                unset($result->name_ta);
            }
            return response()->json(['success' => $results,'statusCode'=>0]);
        }
        else{
            return response()->json(['error' => 'Please provide selected value.','statusCode'=>-99]);

        }
    }
}
