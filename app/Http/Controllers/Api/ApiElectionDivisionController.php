<?php

namespace App\Http\Controllers\APi;

use App\ElectionDivision;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiElectionDivisionController extends Controller
{
    public function getByDistrict(Request $request)
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
        $results = ElectionDivision::where('iddistrict',$id)->latest()->select(['idelection_division',$lang,$fallBack])->where('status',1)->get();
        foreach ($results as $result) {
            $result['label'] = $result[$lang] != null ? $result[$lang] : $result[$fallBack];
            $result['id'] = $result['idelection_division'];
            unset($result->idelection_division);
            unset($result->name_en);
            unset($result->name_si);
            unset($result->name_ta);
        }
        return response()->json(['success'  => $results,'statusCode'=>0]);
    }
}
