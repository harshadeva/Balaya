<?php

namespace App\Http\Controllers;

use App\Category;
use App\ElectionDivision;
use App\MainCategory;
use App\Office;
use App\Post;
use App\PostResponse;
use App\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostResponseController extends Controller
{
    public function viewComments(Request $request){
        $postNo = $request['post_no'];
        $post = Post::where('post_no',$postNo)->where('idoffice',Auth::user()->idoffice)->first();
        if($post != null){
            $commenters = $post->responses()->where('is_admin',0)->get()->groupBy('idUser');
            return view('post.comments')->with(['title'=>'View Comments','commenters'=>$commenters]);
        }
        else{
            return redirect()->back()->with(['error'=>'Invalid post']);
        }
    }

    public function viewUserComments(Request $request){
        $responseId = $request['responseId'];
        $user = $request['user'];
        $postNo = $request['post_no'];
        $post = Post::where('idoffice',Auth::user()->idoffice)->where('post_no',$postNo)->first();
        if($post == null || $user == null){
            return redirect()->back()->with(['error'=>'Invalid post or user']);
        }
        else{
            $commenters = $post->responses()->where('idUser',$user)->whereIn('status',[1,2])->get();
            return view('post.user_comments')->with(['title'=>'View User Comments','commenters'=>$commenters,'user'=>$user,'post_no'=>$postNo,'responseId'=>$responseId]);
        }
    }

    public function store(Request $request){
        $validationMessages = [
            'post_no.required' => 'Process invalid.Please refresh page and try again!',
            'post_no.numeric' => 'Process invalid.Please refresh page and try again!',
            'user_id.required' => 'Process invalid.Please refresh page and try again!',
            'user_id.numeric' => 'Process invalid.Please refresh page and try again!',
            'comment.required' => 'Please write some text!',
            'comment.max' => 'Comment max characters size exeeded!',
        ];

        $validator = \Validator::make($request->all(), [
            'post_no' => 'required|numeric',
            'user_id' => 'required|numeric',
            'comment' => 'required|max:10000',

        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $post = Post::where('post_no',$request['post_no'])->where('idoffice',Auth::user()->idoffice)->first();
        if($post == null){
            return response()->json(['errors' =>['error'=>'Process invalid.Please refresh page and try again!']]);
        }

        //validation end

        $response = new PostResponse();
        $response->idPost = $post->idPost;
        $response->idUser = $request['user_id'];
        $response->response = $request['comment'];
        if($post->office->analysis_available == 1){
            $response->categorized = 0;// uncategorized when creating
        }
        else{
            $response->categorized = 2;// analysis module unpurchased
        }
        $response->is_admin = 1;// value for admin creating response
        $response->attachment = '';// no value for text
        $response->size = 0; // not value for text
        $response->response_type = 1;// text response
        if(Auth::user()->iduser_role == 3){
            $response->status = 1; // office admin comment publish directly
        }
        else{
            $response->status = 2; // managment comment que to office admin
        }
        $response->save();
        return response()->json(['success' =>'message sent']);

    }

    public function storeAttachments(Request $request){
        $validationMessages = [
            'post_no.required' => 'Process invalid.Please refresh page and try again!',
            'post_no.numeric' => 'Process invalid.Please refresh page and try again!',
            'user_id.required' => 'Process invalid.Please refresh page and try again!',
            'user_id.numeric' => 'Process invalid.Please refresh page and try again!',
            'imageFiles.*.file' => 'Image file invalid!',
            'imageFiles.*.image' => 'Image file invalid!',
            'imageFiles.*.mimes' => 'Image file format invalid!',
            'imageFiles.*.max' => 'Image file should less than 5MB!',
            'videoFiles.*.file' => 'Video file invalid!',
            'videoFiles.*.image' => 'Video file invalid!',
            'videoFiles.*.mimes' => 'Video file format invalid!',
            'videoFiles.*.max' => 'Video file should less than 20MB!',
            'audioFiles.*.file' => 'Audio file invalid!',
            'audioFiles.*.image' => 'Audio file invalid!',
            'audioFiles.*.mimes' => 'Audio file format invalid!',
            'audioFiles.*.max' => 'Audio file should less than 20MB!',
        ];

        $validator = \Validator::make($request->all(), [
            'post_no' => 'required|numeric',
            'user_id' => 'required|numeric',
            'imageFiles.*' => 'nullable|file|image|mimes:jpeg,png,gif,webp|max:5048',
            'videoFiles.*' => 'nullable|mimes:mp4,mov,ogg,qt | max:20000',
            'audioFiles.*' => 'nullable|mimes:mpga,wav | max:10000',


        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $post = Post::where('post_no',$request['post_no'])->where('idoffice',Auth::user()->idoffice)->first();
        if($post == null){
            return response()->json(['errors' =>['error'=>'Process invalid.Please refresh page and try again!']]);
        }

        $office = $post->user->idoffice;
        if($office == null){
            return response()->json(['errors' =>['error'=>'Process invalid.Please refresh page and try again!']]);
        }

//save images
        $images = $request->file('imageFiles');
        if (!empty($images)) {
            foreach ($images as $image) {
                $imageName = 'image' . uniqid() . rand(10, 100) . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/' . Office::find($office)->random . '/comments/images', $image, $imageName);

                $response = new PostResponse();
                $response->idPost = $post->idPost;
                $response->idUser = $request['user'];
                $response->idUser = $request['user_id'];
                $response->response = '';
                if($post->office->analysis_available == 1){
                    $response->categorized = 0;// uncategorized when creating
                }
                else{
                    $response->categorized = 2;// analysis module unpurchased
                }
                $response->is_admin = 1;// value for admin creating response
                $response->attachment = $imageName;
                $response->size = $image->getSize();
                $response->response_type = 2;// image
                if(Auth::user()->iduser_role == 3){
                    $response->status = 1; // office admin comment publish directly
                }
                else{
                    $response->status = 2; // managment comment que to office admin
                }
                $response->save();
            }
        }
        //save images end

        //save video
        $videos = $request->file('videoFiles');
        if (!empty($videos)) {
            foreach ($videos as $video) {
                $videoName = 'video' . uniqid() . rand(10, 100) . '.' . $video->getClientOriginalExtension();
                Storage::putFileAs('public/' . Office::find($office)->random . '/comments/videos', $video, $videoName);

                $response = new PostResponse();
                $response->idPost = $post->idPost;
                $response->idUser = $request['user'];
                $response->idUser = $request['user_id'];
                $response->response = '';
                $response->categorized = 0;// uncategorized when creating
                $response->is_admin = 1;// value for admin creating response
                $response->attachment = $videoName;
                $response->size = $video->getSize();
                $response->response_type = 3;// video
                $response->status = 1;
                $response->save();
            }
        }
        //save video end

        //save audio
        $audios = $request->file('audioFiles');
        if (!empty($audios)) {
            foreach ($audios as $audio) {
                $audioName = 'audio' . uniqid() . rand(10, 100) . '.' . $audio->getClientOriginalExtension();
                Storage::putFileAs('public/' . Office::find($office)->random . '/comments/audios', $audio, $audioName);

                $response = new PostResponse();
                $response->idPost = $post->idPost;
                $response->idUser = $request['user'];
                $response->idUser = $request['user_id'];
                $response->response = '';
                $response->categorized = 0;// uncategorized when creating
                $response->is_admin = 1;// value for admin creating response
                $response->attachment = $audioName;
                $response->size = $audio->getSize();
                $response->response_type = 4;// audio
                $response->status = 1;
                $response->save();
            }
        }
        //save audio end

        return response()->json(['success' =>'file sent']);
    }

    public function getCommentByUserAndPost(Request $request){
        $validator = \Validator::make($request->all(), [
            'post_no' => 'required|numeric',
            'user_id' => 'required|numeric',
        ],
        [
            'post_no.required' => 'Process invalid.Please refresh page and try again!',
            'post_no.numeric' => 'Process invalid.Please refresh page and try again!']);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $post = Post::where('post_no',$request['post_no'])->where('idoffice',Auth::user()->idoffice)->first();
        if($post == null){
            return response()->json(['errors' =>['error'=>'Process invalid.Please refresh page and try again!']]);
        }

        //validation end

       $responses =  $post->responses()->with('post')->where('idPost',$post->idPost)->where('idUser',$request['user_id'])->whereIn('status',[1,2])->orderBy('created_at')->get();
       return response()->json(['success' =>$responses]);
    }

    public function publishManagementComment(Request $request){
       $id = $request['id'];
       $response = PostResponse::find(intval($id));
       if($response == null){
           return response()->json(['errors' =>['error'=>'Process invalid.']]);
       }
       else{
           if($response->status == 2){
               $response->status = 1;
               $response->save();
           }
       }
        return response()->json(['success' =>'success']);
    }

    public function rejectManagementComment(Request $request){
        $id = $request['id'];
        $response = PostResponse::find(intval($id));
        if($response == null){
            return response()->json(['errors' =>['error'=>'Process invalid.']]);
        }
        else{
            if($response->status == 2){
                $response->status = 0;
                $response->save();
            }
        }
        return response()->json(['success' =>'success']);
    }

    public function pendingResponses(Request $request){
        $query = PostResponse::query();

        $responses = $query->where('idoffice',Auth::user()->idoffice)->where('status',2)->get();
        return response()->json(['success' =>$responses]);
    }

    public function pending(Request $request){

        $searchText = $request['searchText'];
        $searchCol = $request['searchCol'];

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

            $query->where(function ($q) use ( $request) {
                $q->orWhereHas('user', function ($q)  {
                    $q->where('idoffice', Auth::user()->idoffice);
                });
            });

            $responses = $query->where('is_admin', 1)->where('status', 2)->get();

        $staffDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status', 1)->get();
        return view('comment.pending')->with(['staffDivisions' => $staffDivisions, 'title' => 'Pending Comments', 'responses' => $responses]);

    }

    public function viewPendingResponse(Request $request){

        $responseId = $request['responseId'];
        $response = PostResponse::find(intval($responseId));
        $post = $response->post;
        return view('comment.comment_approve')->with(['title' => 'Approve Comment','post'=>$post,'response'=>$response]);

    }
}
