<?php

namespace App\Http\Controllers\Api;

use App\Agent;
use App\MemberAgent;
use App\Post;
use App\PostView;
use App\ResponsePanel;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiPostController extends Controller
{
    public function getPosts(Request $request)
    {
        $apiLang = $request['lang'];
        $fallBack = 'title_en';
        $fallBackText = 'text_en';
        if ($apiLang == 'si') {
            $lang = 'title_si';
            $langText = 'text_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'title_ta';
            $langText = 'text_ta';
        } else {
            $lang = 'title_en';
            $langText = 'text_en';
        }

        $user = Auth::user();
        if($user->iduser_role == 7){
            $agent = Agent::find( Auth::user()->member->current_agent);
            if($agent == null){
                return response()->json(['error' => 'Agent not found!','statusCode'=>-99]);
            }
//            elseif(MemberAgent::where('idmember',$user->member->idmember)->where('idagent',$agent->idagent)->first() == null || MemberAgent::where('idmember',$user->member->idmember)->where('idagent',$agent->idagent)->first()->status != 1){
//                return response()->json(['error' => 'You are not able to get post from selected agent.!','statusCode'=>-99]);
//            }
            $office = User::find($agent->idUser)->idoffice;
        }
        else{
            $office = $user->idoffice;
        }

        $posts = Post::where(function ($q) use ($user) {
            $q->whereHas('postVillages', function ($q) use ($user) {
                $q->where('idvillage', $user->getType->idvillage);
            })->orWhereHas('postGramasewaDivision', function ($q) use ($user) {
                $q->where('idgramasewa_division', $user->getType->idgramasewa_division)->where('allChild', 1);
            })->orWhereHas('postPollingBooths', function ($q) use ($user) {
                $q->where('idpolling_booth', $user->getType->idpolling_booth)->where('allChild', 1);
            })->orWhereHas('postElectionDivisions', function ($q) use ($user) {
                $q->where('idelection_division', $user->getType->idelection_division)->where('allChild', 1);
            })->orWhereHas('postDistrict', function ($q) use ($user) {
                $q->where('iddistrict', $user->getType->iddistrict)->where('allChild', 1);
            });
        })->where(function ($q) use ($user) {
            $q->orWhere('ethnicities', 0)->orWhereHas('postEthnicities', function ($q) use ($user) {
                $q->where('idethnicity', $user->getType->idethnicity);
            });
        })->where(function ($q) use ($user) {
            $q->orWhere('religions', 0)->orWhereHas('postReligions', function ($q) use ($user) {
                $q->where('idreligion', $user->getType->idreligion);
            });
        })->where(function ($q) use ($user) {
            $q->orWhere('careers', 0)->orWhereHas('postCareers', function ($q) use ($user) {
                $q->where('idcareer', $user->getType->idcareer);
            });
        })->where(function ($q) use ($user) {
            $q->orWhere('educations', 0)->orWhereHas('postEducations', function ($q) use ($user) {
                $q->where('ideducational_qualification', $user->getType->ideducational_qualification);
            });
        })->where(function ($q) use ($user) {
            $q->orWhere('incomes', 0)->orWhereHas('postIncomes', function ($q) use ($user) {
                $q->where('idnature_of_income', $user->getType->idnature_of_income);
            });
        })->where(function ($q) use ($user) {
            $q->where('job_sector', null)->orWhere('job_sector',$user->getType->is_government);
        })->where(function ($q) use ($user) {
            $q->where('preferred_gender', null)->orWhere('preferred_gender',$user->gender);
        })->where(function ($q) use ($user) {
            $q->where('minAge', 0)->orWhere('minAge','<=',$user->age);
        })->where(function ($q) use ($user) {
            $q->where('maxAge', 120)->orWhere('maxAge','>=',$user->age);
        })->where(function ($q) use ($office) {
            $q->where('idoffice', $office);
        })->where('expire_date','>',date('Y-m-d'))->where('status',1)->select(['idPost','title_en','title_si','title_ta','text_en','text_si','text_ta','post_no','created_at','response_panel'])->latest()->paginate(10);

        foreach ($posts as $post) {

            $responseValue = ResponsePanel::where('idPost',$post->idPost)->where('idUser',Auth::user()->idUser)->first();
            if($responseValue != null){
                $value = $responseValue->value;
            }
            else{
                $value = -1;
            }
            $post['title'] = $post[$lang] != null ? $post[$lang] : $post[$fallBack];
            $post['text'] = $post[$langText] != null ? $post[$langText] : $post[$fallBackText];
            $post['id'] = $post['idPost'];
            $post['commentsCount'] = $post->userCommentsCount($user->idUser);
            $post['panel'] = ['type'=>$post->response_panel,'value'=>$value];
            $post['post_no'] = sprintf('%06d',$post['post_no']);

            unset($post->title_en);
            unset($post->title_si);
            unset($post->title_ta);
            unset($post->text_en);
            unset($post->text_si);
            unset($post->response_panel);
            unset($post->text_ta);
            unset($post->idPost);
            unset($post->$lang);
        }
//        $posts = $posts->reverse();
        return response()->json(['success' =>$posts,'statusCode'=>0]);
    }

    public function viewPost(Request $request){

        $validationMessages = [
            'post_id.required' => 'Process invalid.Please refresh page and try again!',
            'post_id.numeric' => 'Process invalid.Please refresh page and try again!',
            'lang.required' => 'Please provide user language!',
            'lang.in' => 'User language invalid!',
        ];

        $validator = \Validator::make($request->all(), [
            'post_id' => 'required|numeric',
            'lang' => 'required|in:en,si,ta',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(),'statusCode'=>-99]);
        }

        $postId  = $request['post_id'];
        $apiLang = $request['lang'];
        $fallBack = 'title_en';
        $fallBackText = 'text_en';
        if ($apiLang == 'si') {
            $lang = 'title_si';
            $langText = 'text_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'title_ta';
            $langText = 'text_ta';
        } else {
            $lang = 'title_en';
            $langText = 'text_en';
        }

        $isExist = PostView::where('idPost',$postId)->where('idUser',Auth::user()->idUser)->first();
        if($isExist == null){
            $postView = new PostView();
            $postView->idPost = $postId;
            $postView->idUser = Auth::user()->idUser;
            $postView->count = 1;
            $postView->status = 1;//default value
            $postView->save();
        }
        else{
            if(Post::find(intval($postId))->isOnce == 1){
                return response()->json(['error' => 'Sorry! This post content can only view once!','statusCode'=>-99]);
            }
            $isExist->count +=1;
            $isExist->save();
        }

        $post =  Post::with(['apiAttachments'])->select(['title_en','title_si','title_ta','text_en','text_si','text_ta','idPost','post_no'])->find(intval($postId));

        $post['title'] = $post[$lang] != null ? $post[$lang] : $post[$fallBack];
        $post['text'] = $post[$langText] != null ? $post[$langText] : $post[$fallBackText];
        $post['post_no'] = sprintf('%06d',$post['post_no']);
        $post['id'] = $post['idPost'];
        unset($post->title_en);
        unset($post->title_si);
        unset($post->title_ta);
        unset($post->text_en);
        unset($post->text_si);
        unset($post->text_ta);
        unset($post->idPost);
        unset($post->$lang);

        return response()->json(['success' =>$post,'statusCode'=>0]);


    }
}
