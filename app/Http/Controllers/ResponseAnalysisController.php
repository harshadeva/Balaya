<?php

namespace App\Http\Controllers;

use App\Analysis;
use App\Category;
use App\ElectionDivision;
use App\GramasewaDivision;
use App\MainCategory;
use App\PostResponse;
use App\StaffElectionDivisions;
use App\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponseAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $searchText = $request['searchText'];
        $searchCol = $request['searchCol'];
        if (Auth::user()->iduser_role == 8 && Auth::user()->assignedElectionDivisions()->count() > 0) {
            $assignedDivisions = Auth::user()->assignedElectionDivisions()->pluck('idelection_division')->toArray();

            $query = PostResponse::query();
            if ($request['searchDivision'] != null) {
                $assignedDivisions = [$request['searchDivision']];
            }
            if (!empty($searchText)) {
                if($searchCol == 1){
                    $query = $query->whereHas('user', function($q) use($searchText){
                        $q->where('fName',  'like', '%' . $searchText . '%');
                    });
                }
                else if($searchCol == 2){
                    $query = $query->whereHas('post', function($q) use($searchText){
                        $q->where('post_no', $searchText);
                    });
                }
            }
            if (!empty(strtotime($request['start']) && !empty($request['end']))) {
                $startDate = date('Y-m-d', strtotime($request['start']));
                $endDate = date('Y-m-d', strtotime($request['end'] . ' +1 day'));

                $query = $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $query->where(function ($q) use ($assignedDivisions) {
                $q->whereHas('user', function ($q) use ($assignedDivisions) {
                    $q->where('idoffice', Auth::user()->idoffice)->where('status', 1)
                        ->whereHas('agent', function ($q) use ($assignedDivisions) {
                            $q->whereIn('idelection_division', $assignedDivisions);
                        });
                })->orWhereHas('user', function ($q) use ($assignedDivisions) {
                    $q->whereHas('member', function ($q) use ($assignedDivisions) {
                            $q->whereHas('memberAgents',function ($query)use ($assignedDivisions) {
                            $query->where('idoffice',Auth::user()->idoffice)->where('status',1);
                        })->whereIn('idelection_division', $assignedDivisions);

                    });
                });
            });

            $responses = $query->where('is_admin', 0)->where('categorized', 0)->get();

        } else {
            $responses = collect();
        }
        $staffDivisions = StaffElectionDivisions::where('idmedia_staff',Auth::user()->idUser)->where('status', 1)->get();
        return view('analysis.response_analysis')->with(['staffDivisions' => $staffDivisions, 'title' => 'Response Analysis', 'responses' => $responses]);
    }

    public function analyse(Request $request)
    {
        $responseId = $request['responseId'];
        $response = PostResponse::find(intval($responseId));
        $post = $response->post;
        $mainCategories = MainCategory::where('status',1)->get();
        $subCategories = SubCategory::where('status',1)->get();
        $categories = Category::where('status',1)->get();
        return view('analysis.response_analyse')->with(['mainCategories'=>$mainCategories,'title' => 'Analyse Response','post'=>$post,'response'=>$response,'subCategories'=>$subCategories,'categories'=>$categories]);
    }

    public function store(Request $request){
        $validator = \Validator::make($request->all(), [
            'cat' => 'required|exists:category,idcategory',
            'subCat' => 'required|exists:sub_category,idsub_category',
            'mainCat' => 'required|exists:main_category,idmain_category',
            'responseId' => 'required|numeric'

        ], [
            'cat.required' => 'Please select category!',
            'cat.required' => 'Please select category!',
            'subCat.required' => 'Please select sub category!',
            'subCat.required' => 'Please select sub category!',
            'mainCat.required' => 'Please select main category!',
            'mainCat.required' => 'Please select main category!',
            'cat.exists' => 'Selected category is invalid.Please contact system administrator!',
            'responseId.required' => 'Your response is invalid.Please contact system administrator!',
            'responseId.numeric' => 'Your response is invalid.Please contact system administrator!',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $response = PostResponse::find(intval($request['responseId']));

        //Validation end

        $analysis = new Analysis();
        $analysis->base = 1;//Response
        $analysis->referrence_id = $request['responseId'];
        $analysis->idmain_category = $request['mainCat'];
        $analysis->idsub_category = $request['subCat'];
        $analysis->idcategory = $request['cat'];
        $analysis->commenter = $response->idUser;
        $analysis->idoffice = Auth::user()->idoffice;
        $analysis->iddistrict = Auth::user()->office->iddistrict;
        $analysis->idelection_division = $response->user->getType->idelection_division;
        $analysis->idpolling_booth = $response->user->getType->idpolling_booth;
        $analysis->idgramasewa_division = $response->user->getType->idgramasewa_division;
        $analysis->idvillage = $response->user->getType->idvillage;
        $analysis->status = 1;
        $analysis->idUser = Auth::user()->idUser;
        $analysis->save();

        $response->categorized = 1;
        $response->save();
        return response()->json(['success' => 'Post categorized']);
    }
}
