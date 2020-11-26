<?php

namespace App\Http\Controllers\APi;

use App\PollingBooth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiPollingBoothController extends Controller
{
    public function getByElectionDivision(Request $request)
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
        $id = intval($request['id']);
        $results = PollingBooth::where('idelection_division', $id)->latest()->select(['idpolling_booth', $lang, $fallBack])->where('status', 1)->get();
        foreach ($results as $result) {
            $result['label'] = $result[$lang] != null ? $result[$lang] : $result[$fallBack];
            $result['id'] = $result['idpolling_booth'];
            unset($result->idpolling_booth);
            unset($result->name_en);
            unset($result->name_si);
            unset($result->name_ta);
        }
        return response()->json(['success' => $results,'statusCode'=>0]);
    }
}
