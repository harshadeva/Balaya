<?php

namespace App\Http\Controllers;

use App\Agent;
use App\District;
use App\ElectionDivision;
use App\MemberAgent;
use App\Office;
use App\OfficeAdmin;
use App\OfficeSetting;
use App\PollingBooth;
use App\StaffElectionDivisions;
use App\Task;
use App\TaskTypes;
use App\User;
use App\UserRole;
use App\UserTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     *render 'add user' interface
     */
    public function index()
    {
        $userRoles = UserRole::where('status', '1')->where('allow_to_manage_by',Auth::user()->iduser_role)->get();
        $userTitles = UserTitle::where('status', '1')->get();
        if(Auth::user()->iduser_role == 2) {
            $offices = Office::where('status',1)->get();
        }
        else{
            $offices = null;
        }

        return view('user.add_user', ['title' =>  __('add_user_page_title'), 'userRoles' => $userRoles,'userTitles'=>$userTitles,'offices'=>$offices]);
    }


    /**
     *add new user to database
     */
    public function store(Request $request){

        //validation start
        $validationMessages  = [
            'nic.required' => 'NIC No should be provided!',
            'nic.max' => 'NIC No invalid!',
            'nic.unique' => 'NIC No already exist!',
            'title.required' => 'User title should be provided!',
            'title.numeric' => 'User title invalid!',
            'firstName.required' => 'First name should be provided!',
            'firstName.regex' => 'First name can only contain characters!',
            'firstName.max' =>'First name must be less than 50 characters!',
            'lastName.required' => 'Last name should be provided!',
            'lastName.regex' => 'Last name can only contain characters!',
            'lastName.max' =>'Last name must be less than 50 characters!',
            'username.required' => 'Username should be provided!',
            'username.max' => 'Username must be less than 50 characters!',
            'username.unique' => 'Username already taken!',
            'email.email' => 'Email format invalid!',
            'email.max' => 'Email must be less than 255 characters!',
            'password.required' => 'Password should be provided!',
            'password.confirmed' => 'Passwords didn\'t match!',
            'phone.numeric' => 'Phone number can only contain numbers!',
            'userRole.required' => 'User role should be provided!',
            'userRole.exists' => 'User role invalid!',
            'dob.required' => 'Date of birth should be provided!',
            'dob.date' => 'Date of birth format invalid!',
            'dob.before' => 'Date of birth should be a valid birthday!',
            'gender.required' => 'Gender should be provided!',
            'gender.boolean' => 'Gender invalid!'
        ];

        $validator = \Validator::make($request->all(), [
            'userRole' => 'required|exists:user_role,iduser_role',
            'username' => 'required|max:50|unique:usermaster',
            'password' => 'required|confirmed',
            'title' => 'required|numeric',
            'firstName' => 'required',
            'lastName' => 'required',
            'gender' => 'required|numeric',

        ],$validationMessages );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if($request['userRole'] == 2 || $request['userRole'] == 3 || $request['userRole'] == 5 || $request['userRole'] == 6 || $request['userRole'] == 7) {
            $validationRules = [
                'nic' => 'required|max:15|unique:usermaster',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|numeric',
                'dob' => 'required|date|before:today',
            ];

            $validator = \Validator::make($request->all(), $validationRules, $validationMessages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }
        }

        if(Auth::user()->iduser_role == 2){
            if($request['office'] != null){
                $office = $request['office'];
            }
            else{
                return response()->json(['errors' => ['office'=>'Office should be provided!']]);
            }
        }
        else{
            $office = Auth::user()->idoffice;
        }

        if($request['userRole'] == 3){
            $exist = User::where('idoffice',$office)->where('iduser_role',3)->first();
            if($exist != null){
                return response()->json(['errors' => ['error'=>'Office admin has been already created!']]);
            }
        }
        else  if($request['userRole'] == 9){
            $exist = User::where('idoffice',$office)->where('iduser_role',9)->first();
            if($exist != null){
                return response()->json(['errors' => ['error'=>'Media head has been already created!']]);
            }
        }

        if(isset(UserTitle::find(intval($request['title']))->gender) && UserTitle::find(intval($request['title']))->gender != $request['gender'] && $request['gender'] != 3){

            return response()->json(['errors' => ['title'=>'Please re-check title and gender!']]);

        }

        //validation end


        //save in user table
        $user = new User();
        $user->idoffice = $office;
        $user->iduser_role = $request['userRole'];
        $user->iduser_title = $request['title'];
        $user->fName = $request['firstName'];
        $user->lName = $request['lastName'];
        $user->nic = $request['nic'];
        $user->gender = $request['gender'];
        $user->contact_no1 = $request['phone'];
        $user->contact_no2 = null;
        $user->email = $request['email'];
        $user->username = $request['username'];
        $user->password = Hash::make($request['password']);
        $user->bday =  date('Y-m-d', strtotime($request['dob']));
        $user->system_language = 1; // default value for english
        $user->status = 1; // default value for active user
        $user->save();
        //save in user table  end


        //save in selected user role table
        if($user->iduser_role == 3){

            $officeAdmin = new OfficeAdmin();
            $officeAdmin->idUser = $user->idUser;
            $officeAdmin->referral_code = $this->generateReferral($user->idUser);
            $officeAdmin->status = 1;
            $officeAdmin->save();

        }

        //save in selected user role table end

        return response()->json(['success' => 'User Registered Successfully!']);

    }

    /**
     *view all saved user
     */
    public function view(Request $request)
    {
        $userRole = $request['userRole'];
        $searchCol = $request['searchCol'];
        $searchText = $request['searchText'];
        $gender = $request['gender'];
        $endDate = $request['end'];
        $startDate = $request['start'];


        $query = User::query();
        if(Auth::user()->iduser_role <= 2 && !empty( $request['office'])){
            $query = $query->where('idoffice',  $request['office']);
        }
        if (!empty($userRole)) {
            $query = $query->where('iduser_role', $userRole);
        }
        if ($gender != null) {
            $query = $query->where('gender', $gender);
        }
        if (!empty($searchText)) {
            if($searchCol == 1){
                $query = $query->where('fName',  'like', '%' . $searchText . '%');

            }
            else if($searchCol == 2){
                $query = $query->where('lName',  'like', '%' . $searchText . '%');

            }
            else if($searchCol == 3){
                $query = $query->where('nic', $searchText);

            }
            else if($searchCol == 4){
                $query = $query->where('username', $searchText);
            }
        }
        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end']));

            $query = $query->whereBetween('bday', [$startDate, $endDate]);
        }

        if(Auth::user()->iduser_role <= 2){
            $users = $query->where('iduser_role','!=',2)->where(function ($q) use ($request){
                $q->whereIn('iduser_role',[3,4,5,6,8,9,10])->orWhereHas('member', function ($q)  use ($request) {
                    $q->whereHas('memberAgents', function ($q)  use ($request) {
                        $q->where('idoffice', $request['office']);
                    });
                });
            })->latest()->paginate(20);//member get with memberAgent table.bot directly form usermaster table
            $offices = Office::where('status',1)->get();
        }
        else{
            $users = $query->where('idoffice', intval(Auth::user()->idoffice))->where(function ($q){
                $q->whereIn('iduser_role',[3,4,5,6,8,9,10])->orWhereHas('member', function ($q)  {
                    $q->whereHas('memberAgents', function ($q)  {
                        $q->where('idoffice', Auth::user()->idoffice);
                    });
                });
            })->latest()->paginate(20);//member get with memberAgent table.bot directly form usermaster table
            $offices = null;
        }
        $userTitles = UserTitle::where('status', '1')->get();
        $userRoles = UserRole::where('status', '1')->get();
//        if(Auth::user()->iduser_role <= 2){
//            $district = District::with(['electionDivisions.pollingBooths.gramasewaDivisions.villages'])->find(1);
//
//        }
//        else{
//            $district = District::with(['electionDivisions.pollingBooths.gramasewaDivisions.villages'])->find(Auth::user()->office->iddistrict);
//
//        }
//        $someArray = json_decode($district, true);
        $users->appends([
            'userRole' => $request['userRole'],
            'searchCol' => $request['searchCol'],
            'searchText' => $request['searchText'],
            'gender' => $request['gender'],
            'end' => $request['end'],
            'start' => $request['start']
        ]);

        return view('user.view_users', ['title' =>  __('View User'),'userTitles'=>$userTitles, 'users' => $users, 'userRoles' => $userRoles,'offices'=>$offices]);
    }


    /**
     *redirect user to 'user edit' page with selected user details
     */
    public function edit(Request $request)
    {
        if((!isset($request['updateUserId'])) || $request['updateUserId'] == null){
            return redirect()->back();
        }
        $user = User::find(intval($request['updateUserId']));
        if($user == null){
            return redirect()->back();
        }
        if($user->userRole->allow_to_manage_by != Auth::user()->iduser_role){
            return redirect()->back();
        }

        $userRoles = UserRole::where('status', '1')->where('allow_to_manage_by',Auth::user()->iduser_role)->get();
        $userTitles = UserTitle::where('status', '1')->get();
        if(Auth::user()->iduser_role == 2) {
            $offices = Office::where('status', 1)->get();
        }
        else{
            $offices = null;
        }

        return view('user.edit_user', ['title' =>  __('Edit User'),'user'=>$user, 'userRoles' => $userRoles,'userTitles'=>$userTitles,'offices'=>$offices]);

    }

    /**
     *update user details
     */
    public function update(Request $request){

        //validation start
        $validationMessages = [
            'nic.required' => 'NIC No should be provided!',
            'nic.max' => 'NIC No invalid!',
            'title.required' => 'User title should be provided!',
            'title.numeric' => 'User title invalid!',
            'firstName.required' => 'First name should be provided!',
            'firstName.regex' => 'First name can only contain characters!',
            'firstName.max' =>'First name must be less than 50 characters!',
            'lastName.required' => 'Last name should be provided!',
            'lastName.regex' => 'Last name can only contain characters!',
            'lastName.max' =>'Last name must be less than 50 characters!',
            'username.required' => 'Username should be provided!',
            'username.max' => 'Username must be less than 50 characters!',
            'email.email' => 'Email format invalid!',
            'email.max' => 'Email must be less than 255 characters!',
            'password.required' => 'Password should be provided!',
            'password.confirmed' => 'Passwords didn\'t match!',
            'phone.numeric' => 'Phone number can only contain numbers!',
            'phone.digits' => 'Phone number must be 10 digits or empty!',
            'userRole.required' => 'User role should be provided!',
            'userRole.numeric' => 'User role should be provided!',
            'dob.required' => 'Date of birth should be provided!',
            'dob.date' => 'Date of birth format invalid!',
            'dob.before' => 'Date of birth should be a valid birthday!',
            'gender.required' => 'Gender should be provided!',
            'gender.boolean' => 'Gender invalid!',

        ];

        $validator = \Validator::make($request->all(), [
            'userRole' => 'required|numeric',
            'title' => 'required|numeric',
            'firstName' => 'required',
            'lastName' => 'required',
            'gender' => 'required|numeric',
            'username' => 'required|max:50',
            'password' => 'nullable|confirmed',


        ],$validationMessages );


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        if($request['userRole'] == 2 || $request['userRole'] == 3 || $request['userRole'] == 5 || $request['userRole'] == 6 || $request['userRole'] == 7|| $request['userRole'] == 8|| $request['userRole'] == 9|| $request['userRole'] == 10) {

            $validator = \Validator::make($request->all(), [
                'nic' => 'required|max:15',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|numeric|digits:10',
                'dob' => 'required|date|before:today',

            ], $validationMessages);


            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            if(User::where('nic',$request['nic'])->where('idUser','!=',$request['userId'])->first() != null){
                return response()->json(['errors' => ['nic'=>'NIC No already exist!']]);
            }
        }

        if(Auth::user()->iduser_role == 2){
            if($request['office'] != null){
                $office = $request['office'];
            }
            else{
                return response()->json(['errors' => ['office'=>'Office should be provided!']]);
            }
        }
        else{
            $office = Auth::user()->idoffice;
        }

        if(User::where('username',$request['username'])->where('idUser','!=',$request['userId'])->first() != null){
            return response()->json(['errors' => ['username'=>'Username already exist!']]);
        }

        if($request['password'] != null){
           if(!Hash::check($request['oldPassword'],Auth::user()->password )){
               return response()->json(['errors' => ['oldPassword'=>'Old password incorrect!']]);
           }
        }

        if($request['userRole'] == 3){
            $exist = User::where('idoffice',$office)->where('iduser_role',3)->where('idUser','!=',$request['userId'])->first();
            if($exist != null){
                return response()->json(['errors' => ['error'=>'Office admin has been already created!']]);
            }
        }
        else if($request['userRole'] == 9){
            $exist = User::where('idoffice',$office)->where('iduser_role',9)->where('idUser','!=',$request['userId'])->first();
            if($exist != null){
                return response()->json(['errors' => ['error'=>'Media head has been already created!']]);
            }
        }
        if( StaffElectionDivisions::where('idoffice',$office)->where('idmedia_staff',$request['userId'])->first()){
            return response()->json(['errors' => ['error'=>'This user has assigned election divisions.Please remove those before change user role.!']]);
        }

        if(isset(UserTitle::find(intval($request['title']))->gender) && UserTitle::find(intval($request['title']))->gender != $request['gender'] && $request['gender'] != 3){

            return response()->json(['errors' => ['title'=>'Please re-check title and gender!']]);

        }


        //validation end


        //update in user table
        $user = User::find(intval($request['userId']));
        $user->idoffice = $office;
        $user->iduser_role = $request['userRole'];
        $user->iduser_title = $request['title'];
        $user->fName = $request['firstName'];
        $user->lName = $request['lastName'];
        $user->nic = $request['nic'];
        $user->gender = $request['gender'];
        $user->contact_no1 = $request['phone'];
        $user->contact_no2 = null;
        if($request['password'] != null){
            $user->password = Hash::make($request['password']);
        }
        $user->email = $request['email'];
        $user->username = $request['username'];
        $user->bday =  date('Y-m-d', strtotime($request['dob']));

        //login sms alert setting
        if($request['smsAlert'] ==  'on'){
            $user->login_alert = 1;
        }
        else{
            $user->login_alert = 0;
        }
        //login sms alert setting end

        $user->save();
        //update in user table  end

        //update in selected user role details

        //update in selected user role details end


        return response()->json(['success' => 'User Registered Successfully!']);

    }

    public function generateReferral($uid){

        $user  =  User::find(intval($uid));
        $name = $user->fName;
        $permitted_chars = '123456789abcdefghjkmnopqrstuvwxyzABCDEFGHJKMNOPQRSTUVWXYZ';

        $referral =  substr(str_shuffle($permitted_chars), 0, 7);
//        $referral [7] = 2 randoms from row . first name first character . 2 randoms from row . 2 randoms from office name;

        if($user->iduser_role == 3){
            $exist = OfficeAdmin::where('referral_code',$referral)->first();
            if($exist != null){
                $this->generateReferral($uid);
            }
            else{
                return $referral;
            }
        }
        else if($user->iduser_role == 6){
            $exist = Agent::where('refferal_code',$referral)->first();
            if($exist != null){
                $this->generateReferral($uid);
            }
            else{
                return $referral;
            }
        }
        else{
            return $referral;
        }
    }

    public function viewPendingAgents(Request $request){
        $searchCol = $request['searchCol'];
        $searchText = $request['searchText'];
        $gender = $request['gender'];
        $endDate = $request['end'];
        $startDate = $request['start'];

        $query = User::query();

        if ($gender != null) {
            $query = $query->where('gender', $gender);
        }
        if (!empty($searchText)) {
            if($searchCol == 1){
                $query = $query->where('fName', 'like', '%' . $searchText . '%');

            }
            else if($searchCol == 2){
                $query = $query->where('lName',  'like', '%' . $searchText . '%');

            }
            else if($searchCol == 3){
                $query = $query->where('nic', $searchText);

            }


        }
        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end'].'+1 day'));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $users = $query->where('status', 2)->where('idoffice', intval(Auth::user()->idoffice))->where('iduser_role', 6)->latest()->paginate(20);
        $taskTypes = TaskTypes::where('status', 1)->get();
        if($taskTypes != null){
            foreach ($taskTypes as $id=>$key){
                if(Task::where('idoffice',Auth::user()->idoffice)->where('status',1)->where('idtask_type',$key->idtask_type)->first() == null){
                    $taskTypes->forget($id);
                }
            }

        }

        return view('user.pending_requests', ['title' =>  __('Pending Requests'), 'users' => $users,'taskTypes'=>$taskTypes]);

    }

    public function getById(Request $request){
        if(Auth::user()->iduser_role <= 2){
            $user =  User::with(['office','officeAdmin','userRole','userTitle','userTitle','agent.electionDivision','agent.pollingBooth','agent.gramasewaDivision','agent.village','agent.religion','agent.ethnicity','agent.career','agent.educationalQualification','agent.natureOfIncome','member.electionDivision','member.pollingBooth','member.gramasewaDivision','member.village','member.religion','member.ethnicity','member.career','member.educationalQualification','member.natureOfIncome'])->find(intval($request['id']));
        }
        else{
            $user =  User::with(['officeAdmin','userRole','userTitle','agent.electionDivision','agent.pollingBooth','agent.gramasewaDivision','agent.village','agent.religion','agent.ethnicity','agent.career','agent.educationalQualification','agent.natureOfIncome','member.electionDivision','member.pollingBooth','member.gramasewaDivision','member.village','member.religion','member.ethnicity','member.career','member.educationalQualification','member.natureOfIncome'])->where('idUser',intval($request['id']))->where('idoffice',Auth::user()->idoffice)->first();
        }
        if($user != null){
            return response()->json(['success' => $user]);
        }
        else{
            return response()->json(['errors' => ['error'=>'The user you are trying to view is invalid!']]);
        }
    }

    public function approveAgent(Request $request){
        $id  = $request['id'];
        $type  = $request['type'];
        $agent = User::find(intval($id));
        if($agent->idoffice == Auth::user()->idoffice){
            $agent->status = 1;
            $agent->save();

            $agent->agent->status = 1;
            $agent->agent->save();

            app(TaskController::class)->assignTask($request);


            return response()->json(['success' => 'Approved']);

        }
        else{
            return response()->json(['errors' => ['error'=>'Agent approving process invalid!']]);
        }
    }

    public function disable(Request $request){
        $user = User::find(intval($request['id']));
        if($user != null){
            if($user->iduser_role == 7){
                $link = $user->member->memberAgents()->where('idoffice',\Illuminate\Support\Facades\Auth::user()->idoffice)->first();
                if($link != null) {
                    if ($link->status == 1) {
                        $link->status = 0;
                        $link->save();
                    }
                }
                else{
                    return response()->json(['errors' => ['error'=>'User invalid!']]);
                }

            }
            else {
                if ($user->status == 1) {
                    $user->status = 0;
                    $user->save();
                }
            }
        }
        else{
            return response()->json(['errors' => ['error'=>'User invalid!']]);
        }

        return response()->json(['success' => 'disabled']);
    }

    public function enable(Request $request){
        $user = User::find(intval($request['id']));
        if($user != null){
            if($user->iduser_role == 7){

                $link = $user->member->memberAgents()->where('idoffice',\Illuminate\Support\Facades\Auth::user()->idoffice)->first();
                if($link != null) {
                    if ($link->status == 0) {
                        $link->status = 1;
                        $link->save();
                    }
                }
                else{
                    return response()->json(['errors' => ['error'=>'User invalid!']]);
                }
            }
            else {
                $exist = Agent::where('idvillage',$user->agent->idvillage)->whereHas('userBelongs',function ($q) use ($user){
                    $q->where('idoffice',$user->idoffice)->whereIn('status',[1,2,3]);
                })->whereIn('status',[1,2,3])->first();

                if($exist != null){
                    return response()->json(['errors' => ['error'=>'Another active agent already exist in the village!']]);
                }

                if ($user->status == 0) {
                    $user->status = 1;
                    $user->save();
                }
            }
        }
        else{
            return response()->json(['errors' => ['error'=>'User invalid!']]);
        }

        return response()->json(['success' => 'enabled']);
    }

    public function autoMember(Request $request){

        if($request['status'] == 'true'){
            $office = OfficeSetting::where('idoffice',Auth::user()->idoffice)->first();
            if($office == null){
                $office = new OfficeSetting();
                $office->idoffice = Auth::user()->idoffice;
                $office->status = 1;
                $office->agent_auto = 0;
            }
            $office->member_auto = 1;
            $office->save();
        }
        else{
            $office = OfficeSetting::where('idoffice',Auth::user()->idoffice)->first();
            if($office == null){
                $office = new OfficeSetting();
                $office->idoffice = Auth::user()->idoffice;
                $office->status = 1;
                $office->agent_auto = 0;
            }
            $office->member_auto = 0;
            $office->save();
        }

    }

    public function autoAgent(Request $request){

        if($request['status'] == 'true'){
            $office = OfficeSetting::where('idoffice',Auth::user()->idoffice)->first();
            if($office == null){
                $office = new OfficeSetting();
                $office->idoffice = Auth::user()->idoffice;
                $office->status = 1;
                $office->member_auto = 0;
            }
            $office->agent_auto = 1;
            $office->save();
        }
        else{
            $office = OfficeSetting::where('idoffice',Auth::user()->idoffice)->first();
            if($office == null){
                $office = new OfficeSetting();
                $office->idoffice = Auth::user()->idoffice;
                $office->status = 1;
                $office->member_auto = 0;
            }
            $office->agent_auto = 0;
            $office->save();
        }

    }

    public function getMemberByAgent(Request $request){
        $id = $request['id'];
      if($request['type'] == 2){
          return Agent::find(intval($id))->getAppMembers();
      }
      else if($request['type'] == 1){
          return Agent::find(intval($id))->getSmsMembers();
      }
      else{
          return Agent::find(intval($id))->getMembers();
      }
    }

    /**
     *redirect user to 'my profile' page with auth user details
     */
    public function profile()
    {
        $user = Auth::user();
        $userTitles = UserTitle::where('status', '1')->get();

        return view('user.my_profile', ['title' =>  __('My Profile'),'user'=>$user, 'userTitles'=>$userTitles]);
    }


}
