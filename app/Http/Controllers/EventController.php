<?php

namespace App\Http\Controllers;

use App\ElectionDivision;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * @return $this
     */
    public function index(){
        $electionDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status',1)->latest()->get();
        return view('event.create')->with(['title'=>'Create Event','electionDivisions'=>$electionDivisions]);

    }

    public function view(Request $request){
        $electionDivisions = ElectionDivision::where('iddistrict',Auth::user()->office->iddistrict)->where('status',1)->latest()->get();
        $events = Event::where('idoffice',Auth::user()->idoffice)->paginate(15);
        return view('event.view')->with(['title'=>'View Events','electionDivisions'=>$electionDivisions,'events'=>$events]);
    }

    public function store(Request $request){

        $validator = \Validator::make($request->all(), [
            'event_en' => 'required',
            'location_en' => 'required',
            'date' => 'required',
            'time' => 'required'

        ], [
            'event_en.required' => 'Event name  should be provided!',
            'location_en.required' => 'Event location should be provided!',
            'date.required' => 'Event date should be provided!',
            'time.required' => 'Election division (Tamil) should be provided!',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
       //----------------------- Validation end --------------------------------//

        $event = new Event();
        $event->idoffice = Auth::user()->idoffice;
        $event->idUser = Auth::user()->idUser;
        $event->iddistrict = Auth::user()->office->idoffice;
        $event->idelection_division = $request['electionDivision'];
        $event->idpolling_booth = $request['pollingBooth'];
        $event->idgramasewa_division = $request['gramasewaDivision'];
        $event->idvillage = $request['village'];
        $event->name_en = $request['event_en'];
        $event->name_si = $request['event_si'];
        $event->name_ta = $request['event_ta'];
        $event->location_en = $request['location_en'];
        $event->location_si = $request['location_si'];
        $event->location_ta = $request['location_ta'];
        $event->discription_en = $request['description_en'];
        $event->discription_si = $request['description_si'];
        $event->discription_ta = $request['description_ta'];
        $event->date = date('Y-m-d', strtotime($request['date']));
        $event->time = $request['time'];
        $event->status = 1;
        $event->save();

        return response()->json(['success' => 'Event saved']);
    }
}
