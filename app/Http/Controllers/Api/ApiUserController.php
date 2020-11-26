<?php

namespace App\Http\Controllers\Api;

use App\Agent;
use App\Career;
use App\EducationalQualification;
use App\ElectionDivision;
use App\Ethnicity;
use App\GramasewaDivision;
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
use App\UserTitle;
use App\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class ApiUserController extends Controller
{
    public function login(Request $request)
    {
        //validation start
        $validator = \Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'lang' => 'required'
        ], [
            'username.required' => 'Username should be provided!',
            'username.string' => 'Username must be a string!',
            'password.required' => 'Password should be provided!',
            'password.string' => 'Password should be a string!',
            'lang.required' => 'Please provide user language!'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }

        $userMaster = User::where('username', $request->username)->where('iduser_role','!=',7)->where('status','!=',3)->orWhere(function ($q) use ($request){
            $q->where('username', $request->username)->where('iduser_role',7)->whereHas('member',function ($q){
               $q->whereHas('memberAgents',function ($q){
                  $q->whereRaw('idagent = member.current_agent')->where('status','!=',3);
               });
            });
        })->first();
        if ($userMaster != null) {
            if ($userMaster->iduser_role > 2 && $userMaster->iduser_role != 7) {
                if ($userMaster->status != 1) {
                    return response()->json(['error' => 'Your account is not activated yet!', 'statusCode' => -99]);
                }
                if ($userMaster->office->status != 1) {
                    return response()->json(['error' => 'Your office has been disabled.Please contact your administrator!', 'statusCode' => -99]);
                }
            }
        } else {
            return response()->json(['error' => 'Username or Password Incorrect!', 'statusCode' => -99]);
        }

        if ($userMaster->iduser_role == 6) {
            //not specific action yet.only check user role

        } elseif ($userMaster->iduser_role == 7) {
            if ($userMaster->member->memberAgents()->where('idAgent', $userMaster->member->current_agent)->first()->status != 1) {
                return response()->json(['error' => 'You are not a active member of selected agent!', 'statusCode' => -99]);
            }
            if (Office::find(intval($userMaster->member->memberAgents()->where('idAgent', $userMaster->member->current_agent)->first()->idoffice))->status != 1) {
                return response()->json(['error' => 'Agent\'s office has been disabled.Please contact your administrator!', 'statusCode' => -99]);
            }
        } else {
            return response()->json(['error' => 'User Invalid!', 'statusCode' => -99]);
        }

        if(!Hash::check($request['password'],$userMaster->password )){
            return response()->json(['error' => 'Please re-check username and password!', 'statusCode' => -99]);
        }

        if (!Auth::loginUsingId($userMaster->idUser)) {
            return response()->json(['error' => 'Please re-check username and password!', 'statusCode' => -99]);
        }

        $token = Auth::user()->createToken('authToken')->accessToken; //generate access token

        return response()->json(['success' => ['userId' => Auth::user()->idUser,'userRole' => Auth::user()->iduser_role, 'accessToken' => $token], 'statusCode' => 0]);

    }

    public function getById(Request $request)
    {

        $user = User::with(['officeAdmin', 'userRole', 'userTitle', 'agent.electionDivision', 'agent.pollingBooth', 'agent.gramasewaDivision', 'agent.village'])->where('idUser', intval($request['id']))->where('idoffice', Auth::user()->idoffice)->first();
        if ($user != null) {
            return response()->json(['success' => $user, 'statusCode' => 0]);
        } else {
            return response()->json(['error' => 'The user you are trying to view is invalid!', 'statusCode' => -99]);
        }
    }

    public function getOfficeAdminByReferral(Request $request)
    {
        $apiLang = $request['lang'];
        $fallBack = 'name_en';
        $referral = $request['referral_code'];

        $officeAdmin = OfficeAdmin::where('referral_code', $referral)->where('status', 1)->first();

        if ($officeAdmin == null) {
            return response()->json(['error' => 'Office admin referral code invalid!', 'statusCode' => -99]);
        }

        $user = User::find($officeAdmin->idUser);

        if ($apiLang == 'si') {
            $lang = 'name_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'name_ta';
        } else {
            $lang = 'name_en';
        }
        $titles = UserTitle::where('status', 1)->select(['iduser_title', $lang, 'name_en', 'gender'])->get();
        $titles = $this->filterLanguage($titles, $lang, $fallBack, 'iduser_title');

        $ethnicities = Ethnicity::where('status', 1)->select(['idethnicity', $lang, 'name_en'])->get();
        $ethnicities = $this->filterLanguage($ethnicities, $lang, $fallBack, 'idethnicity');

        $religions = Religion::where('status', 1)->select(['idreligion', $lang, 'name_en'])->get();
        $religions = $this->filterLanguage($religions, $lang, $fallBack, 'idreligion');

        $educationQualifications = EducationalQualification::where('status', 1)->select(['ideducational_qualification', $lang, 'name_en'])->get();
        $educationQualifications = $this->filterLanguage($educationQualifications, $lang, $fallBack, 'ideducational_qualification');

        $natureOfIncomes = NatureOfIncome::where('status', 1)->select(['idnature_of_income', $lang, 'name_en'])->get();
        $natureOfIncomes = $this->filterLanguage($natureOfIncomes, $lang, $fallBack, 'idnature_of_income');

        $electionDivisions = ElectionDivision::where('status', 1)->where('iddistrict', $user->office->iddistrict)->select(['idelection_division', $lang, 'name_en'])->get();
        $electionDivisions = $this->filterLanguage($electionDivisions, $lang, $fallBack, 'idelection_division');

        $positions = Position::where('status',1)->select(['idposition', $lang, 'name_en'])->get();
        $positions = $this->filterLanguage($positions, $lang, $fallBack, 'idposition');


        return response()->json(['success' =>
            ['referral_code' => $referral,
                'titles' => $titles,
                'ethnicities' => $ethnicities,
                'religions' => $religions,
                'educationQualifications' => $educationQualifications,
                'natureOfIncomes' => $natureOfIncomes,
                'electionDivisions' => $electionDivisions,
                'positions'=>$positions
            ], 'statusCode' => 0], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);
    }

    public function getAgentByReferral(Request $request)
    {
        $apiLang = $request['lang'];
        $fallBack = 'name_en';
        $referral = $request['referral_code'];

        if ($apiLang == 'si') {
            $lang = 'name_si';
        } elseif ($apiLang == 'ta') {
            $lang = 'name_ta';
        } else {
            $lang = 'name_en';
        }

        $agent = Agent::where('referral_code', $referral)->where('status', 1)->first();

        $titles = UserTitle::where('status', 1)->select(['iduser_title', 'name_en', $lang, 'gender'])->get();
        $titles = $this->filterLanguage($titles, $lang, $fallBack, 'iduser_title');

        $careers = Career::where('status', 1)->select(['idcareer', 'name_en', $lang])->get();
        $careers = $this->filterLanguage($careers, $lang, $fallBack, 'idcareer');

        $ethnicities = Ethnicity::where('status', 1)->select(['idethnicity', 'name_en', $lang])->get();
        $ethnicities = $this->filterLanguage($ethnicities, $lang, $fallBack, 'idethnicity');

        $religions = Religion::where('status', 1)->select(['idreligion', 'name_en', $lang])->get();
        $religions = $this->filterLanguage($religions, $lang, $fallBack, 'idreligion');

        $educationQualifications = EducationalQualification::where('status', 1)->select(['ideducational_qualification', 'name_en', $lang])->get();
        $educationQualifications = $this->filterLanguage($educationQualifications, $lang, $fallBack, 'ideducational_qualification');

        $natureOfIncomes = NatureOfIncome::where('status', 1)->select(['idnature_of_income', 'name_en', $lang])->get();
        $natureOfIncomes = $this->filterLanguage($natureOfIncomes, $lang, $fallBack, 'idnature_of_income');

        $positions = Position::where('status',1)->select(['idposition', $lang, 'name_en'])->get();
        $positions = $this->filterLanguage($positions, $lang, $fallBack, 'idposition');

        if ($agent != null) {
            return response()->json(['success' =>
                ['referral_code' => $referral,
                    'titles' => $titles,
                    'careers' => $careers,
                    'ethnicities' => $ethnicities,
                    'religions' => $religions,
                    'educationQualifications' => $educationQualifications,
                    'natureOfIncomes' => $natureOfIncomes,
                    'positions'=>$positions
                ], 'statusCode' => 0], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json(['error' => 'Agent referral code invalid!', 'statusCode' => -99]);
        }
    }


    /**
    Also calling from ApiProfileController
     */
    public function filterLanguage($collection, $lang, $fallBack, $id)
    {
        foreach ($collection as $item) {
            $item['label'] = $item[$lang] != null ? $item[$lang] : $item[$fallBack];
            $item['id'] = $item[$id];
            unset($item->name_en);
            unset($item->$lang);
            unset($item->$id);
        }
        return $collection;
    }

    public function getMembers()
    {
        if (Auth::user()->iduser_role != 6) {
            return response()->json(['error' => 'You are not an agent', 'statusCode' => -99]);
        }
        $memberAgents = MemberAgent::where('idagent', Auth::user()->agent->idagent)->get();
        foreach ($memberAgents as $memberAgent) {
            $memberAgent['id'] = $memberAgent->idmember_agent;
            $memberAgent['userId'] = $memberAgent->member->idUser;
            $memberAgent['name'] = User::find(Member::find($memberAgent->idmember)->idUser)->fName . ' ' . User::find(Member::find($memberAgent->idmember)->idUser)->lName;
            $memberAgent['requested'] = $memberAgent->created_at->format('Y-m-d');
            $memberAgent['isSms'] = $memberAgent->member->isSms;
            $memberAgent['completedPercentage'] = app(ApiProfileController::class)->completedPercentage($memberAgent->member->idUser);
            if ($memberAgent->status == 0) {
                $memberAgent['status'] = 2;
            } else if ($memberAgent->status == 1) {
                $memberAgent['status'] = 0;
            } else {
                $memberAgent['status'] = 1;
            }

            $memberAgent->makeHidden('member')->toArray();
            $memberAgent->makeHidden('idmember_agent')->toArray();
            $memberAgent->makeHidden('idmember')->toArray();
            $memberAgent->makeHidden('idagent')->toArray();
            $memberAgent->makeHidden('idoffice')->toArray();
            $memberAgent->makeHidden('updated_at')->toArray();
            $memberAgent->makeHidden('created_at')->toArray();


        }
        return response()->json(['success' => $memberAgents, 'statusCode' => 0]);
    }

    public function approveMember(Request $request)
    {
        $validationMessages = [
            'id.required' => 'Record id required!',
            'id.numeric' => 'Record id invalid!',
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required|numeric',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }

        $memberAgent = MemberAgent::where('idmember_agent', $request['id'])->where('idagent', Auth::user()->agent->idagent)->first();
        if ($memberAgent == null) {
            return response()->json(['error' => 'Record invalid', 'statusCode' => -99]);
        }

        if ($memberAgent->status == 2) {
            $memberAgent->status = 1;
            $memberAgent->save();

            app(TaskController::class)->complete(Member::find($memberAgent->idmember)->idUser, Auth::user()->idUser);

            return response()->json(['success' => 'Member Approved!', 'statusCode' => 0]);

        } else {
            return response()->json(['error' => 'Member is not in pending list!', 'statusCode' => -99]);

        }
    }

    public function getAgents()
    {
        if (Auth::user()->iduser_role != 7) {
            return response()->json(['error' => 'You are not a member', 'statusCode' => -99]);
        }
        $memberAgents = MemberAgent::where('idmember', Auth::user()->member->idmember)->get();
        foreach ($memberAgents as $memberAgent) {
//            $memberAgent['id'] = Agent::find($memberAgent->idagent)->idUser;
            $memberAgent['id'] = $memberAgent->idagent;
            $memberAgent['name'] = User::find(Agent::find($memberAgent->idagent)->idUser)->fName . ' ' . User::find(Agent::find($memberAgent->idagent)->idUser)->lName;
            $memberAgent['office'] = User::find(Agent::find($memberAgent->idagent)->idUser)->office->office_name;
            if ($memberAgent->status == 0) {
                $memberAgent['availability'] = 2;
            } else if ($memberAgent->status == 1) {
                $memberAgent['availability'] = 0;
            } else {
                $memberAgent['availability'] = 1;
            }
            $memberAgent['isSelected'] = Member::find(intval($memberAgent->idmember))->current_agent == $memberAgent->idagent ? 1 : 0;
            unset($memberAgent->idmember_agent);
            unset($memberAgent->idmember);
            unset($memberAgent->idagent);
            unset($memberAgent->idoffice);
            unset($memberAgent->status);
            unset($memberAgent->created_at);
            unset($memberAgent->updated_at);

        }
        return response()->json(['success' => $memberAgents, 'statusCode' => 0]);

    }

    public function addAgent(Request $request)
    {
        $validationMessages = [
            'referral_code.required' => 'Please provide referral code!',
        ];

        $validator = \Validator::make($request->all(), [
            'referral_code' => 'required',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }
        if (Auth::user()->iduser_role != 7) {
            return response()->json(['error' => 'You are not a member', 'statusCode' => -99]);
        }

        $referral = $request['referral_code'];

        $agent = Agent::where('referral_code', $referral)->where('status', 1)->first();

        if ($agent == null) {
            return response()->json(['error' => 'Referral code invalid', 'statusCode' => -99]);
        }


        if ($agent->idvillage != Auth::user()->member->idvillage) {
            return response()->json(['error' => 'Agent is not belongs to your village.', 'statusCode' => -99]);

        }

        $isExist = MemberAgent::where('idmember', Auth::user()->member->idmember)->where('idagent', $agent->idagent)->first();
        if ($isExist != null) {
            return response()->json(['error' => 'Agent has been added already.', 'statusCode' => -99]);

        }
        //Validation end//

        $memberAgent = new MemberAgent();
        $memberAgent->idmember = Auth::user()->member->idmember;
        $memberAgent->idagent = $agent->idagent;
        $memberAgent->idoffice = User::find($agent->idUser)->idoffice;
        $memberAgent->status = 2;
        $memberAgent->save();

        return response()->json(['success' => 'Agent added.Please wait for confirmation!', 'statusCode' => 0]);

    }

    public function setAgents(Request $request)
    {

        $validationMessages = [
            'id.required' => 'Please provide agent id!',
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }

        $id = $request['id'];
        $agent = Agent::find(intval($id));

        if ($agent == null) {
            return response()->json(['error' => 'Agent id invalid.', 'statusCode' => -99]);
        }
        $isExist = MemberAgent::where('idmember', Auth::user()->member->idmember)->where('idagent', $agent->idagent)->first();

        if ($isExist == null) {
            return response()->json(['error' => 'Agent id invalid.', 'statusCode' => -99]);
        } else if ($isExist->status != 1) {
            return response()->json(['error' => 'Agent has suspended you.', 'statusCode' => -99]);
        } else {
            $member = Auth::user()->member;
            $member->current_agent = $id;
            $member->save();
        }
        return response()->json(['success' => 'Agent switched successfully.', 'statusCode' => 0]);

    }

    public function activateMember(Request $request)
    {
        //validation start
        $validationMessages = [
            'id.required' => 'Member id not provided!',
            'id.numeric' => 'Member id not provided!',
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required|numeric',

        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }
        if (Auth::user()->iduser_role != 6) {
            return response()->json(['error' => 'You are not an agent', 'statusCode' => -99]);
        }
        $id = $request['id'];

        $memberAgent = MemberAgent::where('idagent', Auth::user()->agent->idagent)->where('idmember_agent', $id)->first();
        if ($memberAgent == null) {
            return response()->json(['error' => 'Id invalid.', 'statusCode' => -99]);
        }
        if ($memberAgent->status == 0) {
            $memberAgent->status = 1;
            $memberAgent->save();
            return response()->json(['success' => 'Member activated.', 'statusCode' => 0]);

        }
        return response()->json(['success' => 'Member already activated.', 'statusCode' => 0]);

    }

    public function deactivateMember(Request $request)
    {
        //validation start
        $validationMessages = [
            'id.required' => 'Member id not provided!',
            'id.numeric' => 'Member id not provided!',
        ];

        $validator = \Validator::make($request->all(), [
            'id' => 'required|numeric',

        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first(), 'statusCode' => -99]);
        }
        if (Auth::user()->iduser_role != 6) {
            return response()->json(['error' => 'You are not an agent', 'statusCode' => -99]);
        }
        $id = $request['id'];

        $memberAgent = MemberAgent::where('idagent', Auth::user()->agent->idagent)->where('idmember_agent', $id)->first();
        if ($memberAgent == null) {
            return response()->json(['error' => 'Id invalid.', 'statusCode' => -99]);
        }
        if ($memberAgent->status == 1) {
            $memberAgent->status = 0;
            $memberAgent->save();
            return response()->json(['success' => 'Member deactivated.', 'statusCode' => 0]);

        }
        return response()->json(['success' => 'Member already deactivated.', 'statusCode' => 0]);

    }

}
