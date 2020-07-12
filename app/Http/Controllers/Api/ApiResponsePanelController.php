<?php

namespace App\Http\Controllers\Api;

use App\Post;
use App\ResponsePanel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiResponsePanelController extends Controller
{
    public function store(Request $request)
    {
        $validationMessages = [
            'post_id.required' => 'Process invalid.Please refresh page and try again!',
            'value.required' => 'Response value is required!',
            'post_id.numeric' => 'Process invalid.Please refresh page and try again!',
        ];

        $validator = \Validator::make($request->all(), [
            'post_id' => 'required|numeric',
            'value' => 'required',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }
        $postId = $request['post_id'];
        $post = Post::find($postId);
        if ($post != null) {
            $isExist = ResponsePanel::where('idPost',$postId)->where('idUser',Auth::user()->idUser)->where('status',1)->first();
            if($isExist != null){
                $isExist->value = $request['value'];
                $isExist->save();
            }
            else{
                $response = new ResponsePanel();
                $response->idUser = Auth::user()->idUser;
                $response->idPost = $postId;
                $response->panel_type = $post->response_panel;
                $response->value = $request['value'];
                $response->status = 1;
                $response->save();
            }
            return response()->json(['success' => 'Response saved', 'statusCode' => 0]);

        } else {
            return response()->json(['error' => 'Post invalid!', 'statusCode' => -99]);
        }

    }
}
