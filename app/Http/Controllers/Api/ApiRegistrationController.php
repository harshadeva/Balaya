<?php

namespace App\Http\Controllers\Api;

use App\Agent;
use App\Career;
use App\EducationalQualification;
use App\ElectionDivision;
use App\Ethnicity;
use App\GramasewaDivision;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\TaskController;
use App\Member;
use App\MemberAgent;
use App\NatureOfIncome;
use App\Office;
use App\OfficeAdmin;
use App\PollingBooth;
use App\Position;
use App\Religion;
use App\Task;
use App\TaskAge;
use App\TaskCareer;
use App\TaskEducation;
use App\TaskEthnicity;
use App\TaskIncome;
use App\TaskReligion;
use App\User;
use App\UserSociety;
use App\UserTitle;
use App\Village;
use App\WelcomeSms;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiRegistrationController extends Controller
{
    public function getByJobSector(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required'
        ], [
            'id.required' => 'Please provide selected job sector!'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }

        $fallBack = 'name_en';
        $apiLang = $request['lang'];

        if ($apiLang == 'si') {
            $lang = 'name_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'name_ta';
        } else {
            $lang = 'name_en';
        }

        $careers = Career::where('status', 1)->where('sector', $request['id'])->select(['idcareer', $lang, 'name_en'])->get();
        foreach ($careers as $item) {
            $item['label'] = $item[$lang] != null ? $item[$lang] : $item[$fallBack];
            $item['id'] = $item['idcareer'];
            unset($item->name_en);
            unset($item->$lang);
            unset($item->idcareer);
        }

        return response()->json(['success' => $careers, 'statusCode' => 0]);

    }

    /**
     *add new user to database
     */
    public function store(Request $request)
    {
        //validation start
        $validator = \Validator::make($request->all(), [
            'userRole' => 'required|exists:user_role,iduser_role',
            'username' => 'nullable|max:50|unique:usermaster',
            'password' => 'required|confirmed',
            'title' => 'nullable|numeric',
            'firstName' => 'required',
            'lastName' => 'nullable',
            'isGovernment' => 'nullable',
            'gender' => 'nullable|numeric',
            'nic' => 'required|max:15|unique:usermaster',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|regex:/^[0-9]*$/|min:10',
            'dob' => 'nullable|date|before:today',
            'ethnicity' => 'nullable|exists:ethnicity,idethnicity',
            'religion' => 'nullable|exists:religion,idreligion',
            'career' => 'nullable|exists:career,idcareer',
            'educationalQualification' => 'nullable|exists:educational_qualification,ideducational_qualification',
            'natureOfIncome' => 'nullable|exists:nature_of_income,idnature_of_income',
            'branchSociety' => 'nullable|numeric',
            'womenSociety' => 'nullable|numeric',
            'youthSociety' => 'nullable|numeric',

        ], [
            'nic.required' => 'NIC No should be provided!',
            'nic.max' => 'NIC No invalid!',
            'nic.unique' => 'NIC No already exist!',
            'title.required' => 'User title should be provided!',
            'title.numeric' => 'User title invalid!',
            'firstName.required' => 'First name should be provided!',
            'firstName.regex' => 'First name can only contain characters!',
            'firstName.max' => 'First name must be less than 50 characters!',
            'lastName.required' => 'Last name should be provided!',
            'lastName.regex' => 'Last name can only contain characters!',
            'lastName.max' => 'Last name must be less than 50 characters!',
            'username.required' => 'Username should be provided!',
            'username.max' => 'Username must be less than 50 characters!',
            'username.unique' => 'Username already taken!',
            'email.email' => 'Email format invalid!',
            'email.max' => 'Email must be less than 255 characters!',
            'isGovernment.required' => 'Job sector should be provided!',
            'password.required' => 'Password should be provided!',
            'password.confirmed' => 'Passwords didn\'t match!',
            'phone.regex' => 'Phone number can only contain numbers!',
            'phone.min' => 'Phone number should contains 10 numbers!',
            'userRole.required' => 'User role should be provided!',
            'userRole.exists' => 'User role invalid!',
            'dob.required' => 'Date of birth should be provided!',
            'dob.date' => 'Date of birth format invalid!',
            'dob.before' => 'Date of birth should be a valid birthday!',
            'gender.required' => 'Gender should be provided!',
            'gender.numeric' => 'Gender invalid!',
            'ethnicity.required' => 'Ethnicity should be provided!',
            'ethnicity.exists' => 'Ethnicity invalid!',
            'religion.required' => 'Religion should be provided!',
            'religion.exists' => 'Religion invalid!',
            'career.required' => 'Career should be provided!',
            'career.exists' => 'Career invalid!',
            'educationalQualification.required' => 'Educational qualification should be provided!',
            'educationalQualification.exists' => 'Educational qualification invalid!',
            'natureOfIncome.required' => 'Nature of income should be provided!',
            'natureOfIncome.exists' => 'Nature of income invalid!',
            'branchSociety.numeric' => 'Branch society invalid!',
            'womenSociety.numeric' => 'Women\'s society invalid!',
            'youthSociety.numeric' => 'Youth invalid!',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }

        if ($request['userRole'] == 6) {
            if ($request['referral_code'] == null) {
                return response()->json(['error' => 'Office referral code should be provided!', 'statusCode' => -99]);
            }
            $officeAdmin = OfficeAdmin::where('referral_code', $request['referral_code'])->whereIn('status', [1, 2])->first();
            if ($officeAdmin == null) {
                return response()->json(['error' => 'Referral code invalid!', 'statusCode' => -99]);
            }
            $office = User::find($officeAdmin->idUser)->idoffice;
            $district = Office::find(intval($office))->iddistrict;
            $parentOffice = User::find($officeAdmin->idUser)->office;

            if ($request['electionDivision'] == null) {
                return response()->json(['error' => 'Election division should be provided!', 'statusCode' => -99]);
            } else if (ElectionDivision::where('idelection_division', $request['electionDivision'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['error' => ['electionDivision' => 'Election division invalid!']]);
            }
            if ($request['pollingBooth'] == null) {
                return response()->json(['error' => 'Polling booth should be provided!', 'statusCode' => -99]);
            } else if (PollingBooth::where('idpolling_booth', $request['pollingBooth'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['error' => 'Polling booth invalid!', 'statusCode' => -99]);
            }
            if ($request['gramasewaDivision'] == null) {
                return response()->json(['error' => 'Gramasewa division should be provided!', 'statusCode' => -99]);
            } else if (GramasewaDivision::where('idgramasewa_division', $request['gramasewaDivision'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['error' => 'Gramasewa division invalid!', 'statusCode' => -99]);
            }
            if ($request['village'] == null) {
                return response()->json(['error' => 'Village should be provided!', 'statusCode' => -99]);
            } else if (Village::where('idvillage', $request['village'])->where('iddistrict', $district)->first() == null) {
                return response()->json(['error' => 'Village invalid!', 'statusCode' => -99]);
            }
            $exist = Agent::where('idvillage',$request['village'])->whereHas('userBelongs',function ($q) use ($office){
                $q->where('idoffice',$office)->whereIn('status',[1,2,3]);
            })->whereIn('status',[1,2,3])->first();
            if($exist != null){
                return response()->json(['error' => 'Agent has already assigned for this village!', 'statusCode' => -99]);
            }
        } else if ($request['userRole'] == 7) {
            if ($request['referral_code'] == null) {
                return response()->json(['error' => 'Agent referral code should be provided!', 'statusCode' => -99]);
            }
            $agent = Agent::where('referral_code', $request['referral_code'])->whereIn('status', [1, 2])->first();

            if ($agent == null) {
                return response()->json(['error' => 'Referral code invalid!', 'statusCode' => -99]);
            }

            $office = User::find(intval($agent->idUser))->idoffice;
            $district = Office::find(intval($office))->iddistrict;
            $parentOffice = User::find(intval($agent->idUser))->office;

        } else {
            return response()->json(['error' => 'User role unknown!', 'statusCode' => -99]);
        }

        $memberStatus = $parentOffice->officeSetting != null && $parentOffice->officeSetting->member_auto == 1 ? 1 : 2;
        $agentStatus = $parentOffice->officeSetting != null && $parentOffice->officeSetting->agent_auto == 1 ? 1 : 2;

        $request = $this->customRegistrationValidation($request);

        //---------------------------------------------validation end-------------------------------------------------//

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
        $user->bday = $request['dob'];
        $user->system_language = $request['lang']; // default value for english
        if ($user->iduser_role == 6) {
            $user->status = $agentStatus; // value for agent form setting table

        } else if ($user->iduser_role == 7) {
            $user->status = $memberStatus; // value for member form setting table
        }

        $user->save();
        //save in user table  end

        //save in selected user role table
        if ($user->iduser_role == 6) {
            $this->registerAgent($user,$request,$district,$agentStatus);

        } else if ($user->iduser_role == 7) {
            $this->registerMember($user,$request,$district,$agent,$memberStatus);
        }
        // save in selected user role table end

        return response()->json(['success' => 'User registered successfully!', 'statusCode' => 0]);
    }

    public function registerAgent($user,$request,$district,$agentStatus){

        $agent = new Agent();
        $agent->idUser = $user->idUser;
        $agent->iddistrict = $district;
        $agent->idelection_division = $request['electionDivision'];
        $agent->idpolling_booth = $request['pollingBooth'];
        $agent->idgramasewa_division = $request['gramasewaDivision'];
        $agent->idvillage = $request['village'];
        $agent->idethnicity = $request['ethnicity'];
        $agent->idreligion = $request['religion'];
        $agent->ideducational_qualification = $request['educationalQualification'];
        $agent->idnature_of_income = $request['natureOfIncome'];
        $agent->idcareer = $request['career'];
        $agent->homeNo = $request['houseNo'];
        $agent->referral_code = $this->generateReferral($user->idUser);
        $agent->is_government = $request['isGovernment'];
        $agent->isSms = 0;// non sms user
        $agent->status = $agentStatus;// value for pending user
        $agent->save();

        if($agentStatus == 1){
            app(TaskController::class)->assignDefaultTask($user->idUser);
        }
    }

    public function registerMember($user,$request,$district,$agent,$memberStatus){
        $referralAgent = Agent::where('referral_code', $request['referral_code'])->first();

        $member = new Member();
        $member->idUser = $user->idUser;
        $member->iddistrict = $district;
        $member->idelection_division = $agent->idelection_division;
        $member->idpolling_booth = $agent->idpolling_booth;
        $member->idgramasewa_division = $agent->idgramasewa_division;
        $member->idvillage = $agent->idvillage;
        $member->idethnicity = $request['ethnicity'];
        $member->idreligion = $request['religion'];
        $member->ideducational_qualification = $request['educationalQualification'];
        $member->idnature_of_income = $request['natureOfIncome'];
        $member->idcareer = $request['career'];
        $member->current_agent = $referralAgent->idagent;
        $member->is_government = $request['isGovernment'];
        $member->homeNo = $request['houseNo'];
        $member->isSms = 0;// non sms user
        $member->status = 1;// always 1 for member . only change member_agent table status
        $member->save();

        $memberAgent = new MemberAgent();
        $memberAgent->idmember = $member->idmember;
        $memberAgent->idagent = $referralAgent->idagent;
        $memberAgent->idoffice = User::find($referralAgent->idUser)->idoffice;
        $memberAgent->status = $memberStatus; //pending member
        $memberAgent->save();

        if($memberStatus == 1){
            app(TaskController::class)->complete(Member::find($memberAgent->idmember)->idUser, $referralAgent->idUser);
        }
    }


    public function generateReferral($uid)
    {
        $user = User::find(intval($uid));
        $permitted_chars = '123456789abcdefghjkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';

        $referral = substr(str_shuffle($permitted_chars), 0, 7);
//        $referral [7] = 2 randoms from row . first name first character . 2 randoms from row . 2 randoms from office name;

        if ($user->iduser_role == 3) {
            $exist = OfficeAdmin::where('referral_code', $referral)->first();
            if ($exist != null) {
                $this->generateReferral($uid);
            } else {
                return $referral;
            }
        } else if ($user->iduser_role == 6) {
            $exist = Agent::where('referral_code', $referral)->first();
            if ($exist != null) {
                $this->generateReferral($uid);
            } else {
                return $referral;
            }
        } else {
            return $referral;
        }
    }

    public function storeUserSocieties($request,$user,$office){

        if($request['branchSociety'] != null){
            $userSociety = new UserSociety();
            $userSociety->idposition = $request['branchSociety'];
            $userSociety->idoffice = $office;
            $userSociety->idsociety = 1;
            $userSociety->idUser = $user->idUser;
            $userSociety->status = 1;
            $userSociety->save();
        }
        if($request['womenSociety'] != null){
            $userSociety = new UserSociety();
            $userSociety->idposition = $request['womenSociety'];
            $userSociety->idoffice = $office;
            $userSociety->idsociety = 2;
            $userSociety->idUser = $user->idUser;
            $userSociety->status = 1;
            $userSociety->save();
        }
        if($request['youthSociety'] != null){
            $userSociety = new UserSociety();
            $userSociety->idposition = $request['youthSociety'];
            $userSociety->idoffice = $office;
            $userSociety->idsociety = 3;
            $userSociety->idUser = $user->idUser;
            $userSociety->status = 1;
            $userSociety->save();
        }
    }

    public function storeSmsUser(Request $request)
    {

        //validation start
        $validator = \Validator::make($request->all(), [
            'userRole' => 'required|exists:user_role,iduser_role',
            'username' => 'nullable|max:50|unique:usermaster',
            'title' => 'nullable|numeric',
            'firstName' => 'required',
            'lastName' => 'nullable',
            'isGovernment' => 'nullable',
            'gender' => 'nullable|numeric',
            'nic' => 'required|max:15|unique:usermaster',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|regex:/^[0-9]*$/|min:10',
            'dob' => 'nullable|date|before:today',
            'ethnicity' => 'nullable|exists:ethnicity,idethnicity',
            'religion' => 'nullable|exists:religion,idreligion',
            'career' => 'nullable|exists:career,idcareer',
            'educationalQualification' => 'nullable|exists:educational_qualification,ideducational_qualification',
            'natureOfIncome' => 'nullable|exists:nature_of_income,idnature_of_income',
            'branchSociety' => 'nullable|numeric',
            'womenSociety' => 'nullable|numeric',
            'youthSociety' => 'nullable|numeric',
        ], [
            'nic.required' => 'NIC No should be provided!',
            'nic.max' => 'NIC No invalid!',
            'nic.unique' => 'NIC No already exist!',
            'title.required' => 'User title should be provided!',
            'title.numeric' => 'User title invalid!',
            'firstName.required' => 'First name should be provided!',
            'firstName.regex' => 'First name can only contain characters!',
            'firstName.max' => 'First name must be less than 50 characters!',
            'lastName.required' => 'Last name should be provided!',
            'lastName.regex' => 'Last name can only contain characters!',
            'lastName.max' => 'Last name must be less than 50 characters!',
            'username.required' => 'Username should be provided!',
            'username.max' => 'Username must be less than 50 characters!',
            'username.unique' => 'Username already taken!',
            'email.email' => 'Email format invalid!',
            'email.max' => 'Email must be less than 255 characters!',
            'isGovernment.required' => 'Job sector should be provided!',
            'phone.regex' => 'Phone number can only contain numbers!',
            'phone.min' => 'Phone number should contains 10 numbers!',
            'userRole.required' => 'User role should be provided!',
            'userRole.exists' => 'User role invalid!',
            'dob.required' => 'Date of birth should be provided!',
            'dob.date' => 'Date of birth format invalid!',
            'dob.before' => 'Date of birth should be a valid birthday!',
            'gender.required' => 'Gender should be provided!',
            'gender.numeric' => 'Gender invalid!',
            'ethnicity.required' => 'Ethnicity should be provided!',
            'ethnicity.exists' => 'Ethnicity invalid!',
            'religion.required' => 'Religion should be provided!',
            'religion.exists' => 'Religion invalid!',
            'career.required' => 'Career should be provided!',
            'career.exists' => 'Career invalid!',
            'educationalQualification.required' => 'Educational qualification should be provided!',
            'educationalQualification.exists' => 'Educational qualification invalid!',
            'natureOfIncome.required' => 'Nature of income should be provided!',
            'natureOfIncome.exists' => 'Nature of income invalid!',
            'branchSociety.numeric' => 'Branch society invalid!',
            'womenSociety.numeric' => 'Women\'s society invalid!',
            'youthSociety.numeric' => 'Youth invalid!',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }

        if (Auth::user()->iduser_role != 6) {
            return response()->json(['error' => 'You are not an agent!', 'statusCode' => -99]);
        }

        if ($request['userRole'] == 7) {

            $agent = Agent::where('idUser', Auth::user()->idUser)->where('status', 1)->first();
            if ($agent == null) {
                return response()->json(['error' => 'Referral code invalid!', 'statusCode' => -99]);
            }

            $office = User::find(intval($agent->idUser))->idoffice;
            $district = Office::find(intval($office))->iddistrict;
        } else {
            return response()->json(['error' => 'User role unknown!', 'statusCode' => -99]);
        }

        if(isset(UserTitle::find(intval($request['title']))->gender) && UserTitle::find(intval($request['title']))->gender != $request['gender'] && $request['gender'] != 3){

            return response()->json(['errors' => ['title'=>'Please re-check title and gender!']]);

        }

        $request = $this->customRegistrationValidation($request);

        //------------------------------validation end-----------------------------------//

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
        $user->password = Hash::make($request['nic']);
        $user->bday = $request['dob'];
        $user->system_language = $request['lang']; // default value for english
        $user->status = 1; // value for active user
        $user->save();
        //save in user table  end


        $member = new Member();
        $member->idUser = $user->idUser;
        $member->iddistrict = $district;
        $member->idelection_division = $agent->idelection_division;
        $member->idpolling_booth = $agent->idpolling_booth;
        $member->idgramasewa_division = $agent->idgramasewa_division;
        $member->idvillage = $agent->idvillage;
        $member->idethnicity = $request['ethnicity'];
        $member->idreligion = $request['religion'];
        $member->ideducational_qualification = $request['educationalQualification'];
        $member->idnature_of_income = $request['natureOfIncome'];
        $member->idcareer = $request['career'];
        $member->homeNo = $request['houseNo'];
        $member->current_agent = $agent->idagent;
        $member->is_government = $request['isGovernment'];
        $member->isSms = 1;// sms user
        $member->status = 1;// always 1 for member . only change member_agent table status
        $member->save();

        $memberAgent = new MemberAgent();
        $memberAgent->idmember = $member->idmember;
        $memberAgent->idagent = $agent->idagent;
        $memberAgent->idoffice = User::find($agent->idUser)->idoffice;
        $memberAgent->status = 1; //pending member
        $memberAgent->save();

        //save in society table
        $this->storeUserSocieties($request,$user,$office);

        $welcome = WelcomeSms::where('idoffice', Auth::user()->idoffice)->where('status', 1)->latest()->first();
        if($welcome == null){
            $welcome['body'] = 'Welcome!. ';
        }

        $message =  $welcome['body']. ' Registered NIC no : '. $request['nic'];
        $results[] = app(SmsController::class)->send($message,$user->contact_no1);

        app(TaskController::class)->complete($member->idUser, $agent->idUser);

        return response()->json(['success' => 'User registered successfully!', 'statusCode' => 0]);
    }

    public function getRegistrationForm(Request $request){
        $apiLang = $request['lang'];
        $fallBack = 'name_en';

        if ($apiLang == 'si') {
            $lang = 'name_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'name_ta';
        } else {
            $lang = 'name_en';
        }

        if(Auth::user()->iduser_role != 6){
            return response()->json(['error' => 'You are not an agent', 'statusCode' => -99]);
        }

        $agent = Auth::user()->agent;

        $titles = UserTitle::where('status', 1)->select(['iduser_title', 'name_en', $lang, 'gender'])->get();
        $titles = app(ApiUserController::class)->filterLanguage($titles, $lang, $fallBack, 'iduser_title');

        $careers = Career::where('status', 1)->select(['idcareer', 'name_en', $lang])->get();
        $careers = app(ApiUserController::class)->filterLanguage($careers, $lang, $fallBack, 'idcareer');

        $ethnicities = Ethnicity::where('status', 1)->select(['idethnicity', 'name_en', $lang])->get();
        $ethnicities = app(ApiUserController::class)->filterLanguage($ethnicities, $lang, $fallBack, 'idethnicity');

        $religions = Religion::where('status', 1)->select(['idreligion', 'name_en', $lang])->get();
        $religions = app(ApiUserController::class)->filterLanguage($religions, $lang, $fallBack, 'idreligion');

        $educationQualifications = EducationalQualification::where('status', 1)->select(['ideducational_qualification', 'name_en', $lang])->get();
        $educationQualifications = app(ApiUserController::class)->filterLanguage($educationQualifications, $lang, $fallBack, 'ideducational_qualification');

        $natureOfIncomes = NatureOfIncome::where('status', 1)->select(['idnature_of_income', 'name_en', $lang])->get();
        $natureOfIncomes = app(ApiUserController::class)->filterLanguage($natureOfIncomes, $lang, $fallBack, 'idnature_of_income');

        $positions = Position::where('status',1)->select(['idposition', $lang, 'name_en'])->get();
        $positions = app(ApiUserController::class)->filterLanguage($positions, $lang, $fallBack, 'idposition');

        if ($agent != null) {
            return response()->json(['success' =>
                [   'titles' => $titles,
                    'careers' => $careers,
                    'ethnicities' => $ethnicities,
                    'religions' => $religions,
                    'educationQualifications' => $educationQualifications,
                    'natureOfIncomes' => $natureOfIncomes,
                    'positions'=>$positions
                ], 'statusCode' => 0], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json(['error' => 'Agent invalid!', 'statusCode' => -99]);
        }
    }


    /**
     Method also calling from ApiProfileController updateProfile method
     */
    public function customRegistrationValidation($request){

        //common data
        $request['lastName'] = isset($request['lastName']) && $request['lastName'] != null ? $request['lastName'] :  '';
        $request['username'] = isset($request['username']) && $request['username'] != null ? $request['username'] :  $request['nic'];
        $request['password'] = isset($request['password']) && $request['password'] != null ? $request['password'] :  $request['nic'];
        $request['isGovernment'] = isset($request['isGovernment']) && $request['isGovernment'] != null ? $request['isGovernment'] : 4;
        $request['dob'] = isset($request['dob']) && $request['dob'] != null  && date('Y-m-d', strtotime($request['dob'])) != '1970-01-01' ? date('Y-m-d', strtotime($request['dob'])) : $this->calculateDoB($request['nic']);
        $request['gender'] = isset($request['gender']) && $request['gender'] != null ? $request['gender'] : 4;
        $request['title'] = isset($request['title']) && $request['title'] != null ? $request['title'] : UserTitle::where('name_en','')->where('status',0)->first()->iduser_title;

        //common data end

        //profile data
        $request['ethnicity'] = isset($request['ethnicity']) && $request['ethnicity'] != null ? $request['ethnicity'] : Ethnicity::where('name_en','UNDISCLOSED')->where('status',0)->first()->idethnicity;
        $request['religion'] = isset($request['religion']) && $request['religion'] != null ? $request['religion'] : Religion::where('name_en','UNDISCLOSED')->where('status',0)->first()->idreligion;
        $request['educationalQualification'] = isset($request['educationalQualification']) && $request['educationalQualification'] != null ? $request['educationalQualification'] : EducationalQualification::where('name_en','UNDISCLOSED')->where('status',0)->first()->ideducational_qualification;
        $request['natureOfIncome'] = isset($request['natureOfIncome']) && $request['natureOfIncome'] != null ? $request['natureOfIncome'] : NatureOfIncome::where('name_en','UNDISCLOSED')->where('status',0)->first()->idnature_of_income;
        $request['career'] = isset($request['career']) && $request['career'] != null ? $request['career'] : Career::where('name_en','UNDISCLOSED')->where('status',0)->first()->idcareer;
        //profile data end

        return $request;
    }


    public function calculateDoB($nic){
        $nic = strval($nic);
        //check version
        if(strlen($nic) == 10 ){
            //check year
            $nic = substr($nic,0,-1);
            $first2Letters = substr($nic,0,2);
            $year = intval($first2Letters);

            $middle3Letters = intval(substr($nic,2,3));
            if($middle3Letters < 500){
                $day = $middle3Letters ;
            }
            else{
                $day = $middle3Letters - 500;
            }

            $result = $this->getMonth($year,$day);
            $month = $result['month'];
            $date = $result['date'];

            $birthDay =  $year.'-'.$month.'-'.$date;
            return date('Y-m-d',strtotime($birthDay));

        }
        else if(strlen($nic) > 10){

            //check year
            $first2Letters = substr($nic,0,4);
            $year = intval($first2Letters);

            //check gender
            $middle3Letters = intval(substr($nic,4,3));
            if($middle3Letters < 500){
                $day = $middle3Letters;
            }
            else{
                $day = $middle3Letters - 500;
            }

            $result = $this->getMonth($year,$day);
            $month = $result['month'];
            $date = $result['date'];

            $birthDay =  $year.'-'.$month.'-'.$date;
            return date('Y-m-d',strtotime($birthDay));

        }
        else{
            return '1970-01-01';
        }

    }

    public function getMonth($year,$day){

        $long = 1;
        if($year%4 == 0){
            $long = 2;
        }
        if($day <= 31 ){
            return ['month'=>'01','date'=>$day];
        }
        else if($day <= 31 + 28 + $long ){

            return ['month'=>'02','date'=>$day - (31)];
        }
        else if($day <= 31 + 28 + $long + 31 ){
            return ['month'=>'03','date'=>$day - (31 + 28 + $long)];
        }
        else if($day <= 31 + 28 + $long + 31 + 30 ){
            return ['month'=>'04','date'=>$day - (31 + 28 + $long + 31)];
        }
        else if($day <= 31 + 28 + $long + 31 + 30 + 31){
            return ['month'=>'05','date'=>$day - (31 + 28 + $long + 31 + 30)];
        }
        else if($day <= 31 + 28 + $long + 31 + 30 + 31 + 30){
            return ['month'=>'06','date'=>$day - (31 + 28 + $long + 31 + 30 + 31)];
        }
        else if($day <= 31 + 28 + $long + 31 + 30 + 31 + 30 + 31){
            return ['month'=>'07','date'=>$day - (31 + 28 + $long + 31 + 30 + 31 + 30)];
        }
        else if($day <= 31 + 28 + $long + 31 + 30 + 31 + 30 + 31 + 31){
            return ['month'=>'08','date'=>$day - (31 + 28 + $long + 31 + 30 + 31 + 30 + 31)];
        }
        else if($day <= 31 + 28 + $long + 31 + 30 + 31 + 30 + 31 + 31 + 30){
            return ['month'=>'09','date'=>$day - (31 + 28 + $long + 31 + 30 + 31 + 30 + 31 + 31)];
        }
        else if($day <= 31 + 28 + $long + 31 + 30 + 31 + 30 + 31 + 31 + 30 + 31){
            return ['month'=>'10','date'=>$day - (31 + 28 + $long + 31 + 30 + 31 + 30 + 31 + 31 + 30)];
        }
        else if($day <= 31 + 28 + $long + 31 + 30 + 31 + 30 + 31 + 31 + 30 + 31 + 30){
            return ['month'=>'11','date'=>$day - (31 + 28 + $long + 31 + 30 + 31 + 30 + 31 + 31 + 30 + 31)];
        }
        else if($day <= 31 + 28 + $long + 31 + 30 + 31 + 30 + 31 + 31 + 30 + 31 + 30 + 31){
            return ['month'=>'12','date'=>$day - (31 + 28 + $long + 31 + 30 + 31 + 30 + 31 + 31 + 30 + 31 + 30)];
        }
        else{
            return '00';
        }
    }

}
