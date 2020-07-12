<?php

namespace App\Http\Controllers;

use App\DirectMessage;
use App\Office;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DirectMessageController extends Controller
{
    public function index()
    {
        return view('message.message')->with(['title' => 'Direct Messages']);
    }

    public function loadUsers(Request $request)
    {

        $users = [];
        $messages = DirectMessage::with(['fromUser'])->where('to_idUser', Auth::user()->idUser)->where('is_read', 0)->where('status', 1)->get()->groupBy('from_idUser');
        foreach ($messages as $key => $value) {
            $users[] = ['userName' => User::find($key)->fName, 'id' => $key];
        }
        return $users;
    }

    public function getByUser(Request $request)
    {
        $user = $request['id'];
        $messages = DirectMessage::with(['fromUser'])->where('to_idUser', $user)->where('status', 1)->where('is_read', 0)->get();
        return response()->json(['success' => $messages]);
    }

    public function storeAttachments(Request $request)
    {
        $validationMessages = [
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

        $officeId = Auth::user()->office->idoffice;

//save images
        $images = $request->file('imageFiles');
        if (!empty($images)) {
            foreach ($images as $image) {
                $imageName = 'image' . uniqid() . rand(10, 100) . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/' . Office::find($officeId)->random . '/messages/images', $image, $imageName);

                $response = new DirectMessage();
                $response->from_idUser = Auth::user()->idUser;
                $response->to_idUser = $request['user_id'];
                $response->message = '';
                $response->message_type = 2;//image file
                $response->size = $image->getSize();
                $response->attachment = $imageName;
                $response->status = 1;
                $response->categorized = 0;
                $response->is_read = 0;
                $response->is_admin = 1;// default non read
                $response->save();
            }
        }
        //save images end

        //save video
        $videos = $request->file('videoFiles');
        if (!empty($videos)) {
            foreach ($videos as $video) {
                $videoName = 'video' . uniqid() . rand(10, 100) . '.' . $video->getClientOriginalExtension();
                Storage::putFileAs('public/' . Office::find($officeId)->random . '/messages/videos', $video, $videoName);

                $response = new DirectMessage();
                $response->from_idUser = Auth::user()->idUser;
                $response->to_idUser = $request['user_id'];
                $response->message = '';
                $response->message_type = 3;//video file
                $response->size = $video->getSize();
                $response->attachment = $videoName;
                $response->status = 1;
                $response->categorized = 0;
                $response->is_read = 0;
                $response->is_admin = 1;// default non read
                $response->save();
            }
        }
        //save video end

        //save audio
        $audios = $request->file('audioFiles');
        if (!empty($audios)) {
            foreach ($audios as $audio) {
                $audioName = 'audio' . uniqid() . rand(10, 100) . '.' . $audio->getClientOriginalExtension();
                Storage::putFileAs('public/' . Office::find($officeId)->random . '/messages/audios', $audio, $audioName);

                $response = new DirectMessage();
                $response->from_idUser = Auth::user()->idUser;
                $response->to_idUser = $request['user_id'];
                $response->message = '';
                $response->message_type = 4;//audio file
                $response->size = $audio->getSize();
                $response->attachment = $audioName;
                $response->status = 1;
                $response->categorized = 0;
                $response->is_read = 0;
                $response->is_admin = 1;// default non read
                $response->save();
            }
        }
        //save audio end

        return response()->json(['success' => 'file sent']);
    }

    public function store(Request $request)
    {
        $validationMessages = [
            'user_id.required' => 'Process invalid.Please refresh page and try again!',
            'user_id.numeric' => 'Process invalid.Please refresh page and try again!',
            'message.required' => 'Please write some text!',
            'message.max' => 'Message max characters size exeeded!',
        ];

        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'message' => 'required|max:10000',

        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        //validation end

        $response = new DirectMessage();
        $response->from_idUser = Auth::user()->idUser;
        $response->to_idUser = $request['user_id'];
        $response->message = $request['message'];
        $response->message_type = 1;//audio file
        $response->size = 0;
        $response->attachment = '';
        $response->status = 1;
        $response->categorized = 0;
        $response->is_read = 0;
        $response->is_admin = 1;// default non read
        $response->save();

        return response()->json(['success' => 'message sent']);

    }


    public function getMessageByUser(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|numeric',
        ],
            [
                'user_id.required' => 'Process invalid.Please refresh page and try again!',
                'user_id.numeric' => 'Process invalid.Please refresh page and try again!']);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $messages = DirectMessage::where('from_idUser', $request['user_id'])->where('status', 1)->get();

        //validation end

        return response()->json(['success' => $messages]);
    }
}
