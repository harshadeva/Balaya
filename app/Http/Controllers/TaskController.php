<?php

namespace App\Http\Controllers;

use App\Career;
use App\EducationalQualification;
use App\Ethnicity;
use App\Member;
use App\NatureOfIncome;
use App\OfficeAdmin;
use App\Position;
use App\Religion;
use App\Task;
use App\TaskAge;
use App\TaskBranchSociety;
use App\TaskCareer;
use App\TaskEducation;
use App\TaskEthnicity;
use App\TaskGender;
use App\TaskIncome;
use App\TaskJobSector;
use App\TaskReligion;
use App\TaskTypes;
use App\TaskTypeUser;
use App\TaskWomens;
use App\TaskYouth;
use App\User;
use App\UserSociety;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $careers = Career::where('status', 1)->get();
        $religions = Religion::where('status', 1)->get();
        $incomes = NatureOfIncome::where('status', 1)->get();
        $educations = EducationalQualification::where('status', 1)->get();
        $ethnicities = Ethnicity::where('status', 1)->get();
        $positions = Position::where('status', 1)->get();
        $searchCol = $request['searchCol'];
        $searchText = $request['searchText'];
        $query = User::query();
        if (!empty($searchText)) {
            if ($searchCol == 1) {
                $query = $query->where('fName', 'like', '%' . $searchText . '%');
            } else if ($searchCol == 2) {
                $query = $query->where('lName', 'like', '%' . $searchText . '%');
            } else if ($searchCol == 3) {
                $query = $query->whereHas('agent', function ($q) use ($searchText) {
                    $q->whereHas('village', function ($q) use ($searchText) {
                        $q->where('name_en', 'like', '%' . $searchText . '%');
                    });
                });
            }
        }
        $agents = $query->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->where('status', 1)->paginate(10);
        return view('task.assign_task')->with(['positions' => $positions, 'title' => 'Assign Budget', 'agents' => $agents, 'careers' => $careers, 'religions' => $religions, 'incomes' => $incomes, 'educations' => $educations, 'ethnicities' => $ethnicities]);
    }

    public function storeDefault(Request $request)
    {
        $validationMessages = [
            'totalBudget.required' => 'Number of members should be provided!',
            'taskType.required' => 'Task Type should be provided!',
            'totalBudget.not_in' => 'Number of members should be grater than zero (0)!',
        ];
        $validator = \Validator::make($request->all(), [
            'totalBudget' => 'required|not_in:0',
            'taskType' => 'required',
        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $userId = Auth::user()->idUser;
        $isEthnicity = $request['isEthnicity'] == 'true' ? 1 : 0;
        $isReligion = $request['isReligion'] == 'true' ? 1 : 0;
        $isCareer = $request['isCareer'] == 'true' ? 1 : 0;
        $isIncome = $request['isIncome'] == 'true' ? 1 : 0;
        $isEducational = $request['isEducational'] == 'true' ? 1 : 0;
        $isJobSector = $request['isJobSector'] == 'true' ? 1 : 0;
        $isGender = $request['isGender'] == 'true' ? 1 : 0;
        $isBranch = $request['isBranch'] == 'true' ? 1 : 0;
        $isWomens = $request['isWomens'] == 'true' ? 1 : 0;
        $isYouth = $request['isYouth'] == 'true' ? 1 : 0;

        //Validation end

        $isExist = Task::where('idoffice', Auth::user()->idoffice)->where('idtask_type', $request['taskType'])->where('isDefault', 1)->first();
        if ($isExist != null) {
            $isExist->ethnicities()->delete();
            $isExist->careers()->delete();
            $isExist->incomes()->delete();
            $isExist->religions()->delete();
            $isExist->educations()->delete();
            $isExist->genders()->delete();
            $isExist->job()->delete();
            $isExist->youthSociety()->delete();
            $isExist->womensSociety()->delete();
            $isExist->branchSociety()->delete();
            $isExist->age()->delete();
            $isExist->delete();
        }


        //save in task table
        $task = new Task();
        $task->idUser = $userId;
        $task->assigned_by = Auth::user()->idUser;
        $task->idoffice = Auth::user()->idoffice;
        $task->idtask_type = $request['taskType'];
        $task->isDefault = 1;
        $task->ethnicity = $isEthnicity;
        $task->religion = $isReligion;
        $task->career = $isCareer;
        $task->income = $isIncome;
        $task->education = $isEducational;
        $task->job_sector = $isJobSector;
        $task->gender = $isGender;
        $task->branch = $isBranch;
        $task->womens = $isWomens;
        $task->youth = $isYouth;
        $task->target = intval($request['totalBudget']);
        $task->completed_amount = 0;
        $task->status = 1;
        $task->save();
        //save in task table end


//        if ($isReligion != null) {
//            $religions = $request['religionArray'];
//            if ($religions != null) {
//                foreach ($religions as $religion) {
//                    $taskCareer = new TaskEthnicity();
//                    $taskCareer->idtask = $task->idtask;
//                    $taskCareer->idethnicity = $ethnicity['id'];
//                    $taskCareer->value = $ethnicity['value'];
//                    $taskCareer->completed = 0;
//                    $taskCareer->status = 1;
//                    $taskCareer->save();
//
//                }
//            }
//        }

        //save in community tables
//        if ($request['minAge'] != null) {
//            $taskAge = new TaskAge();
//            $taskAge->idtask = $task->idtask;
//            $taskAge->comparison = $request['ageComparison'];
//            $taskAge->minAge = $request['minAge'];
//            $taskAge->maxAge = $request['maxAge'];
//            $taskAge->status = 1;
//            $taskAge->save();
//        }


        if ($isEthnicity) {
            $ethnicities = $request['ethnicityArray'];
            if ($ethnicities != null) {
                foreach ($ethnicities as $ethnicity) {
                    $taskEthnicity = new TaskEthnicity();
                    $taskEthnicity->idtask = $task->idtask;
                    $taskEthnicity->idethnicity = $ethnicity['id'];
                    $taskEthnicity->value = $ethnicity['value'];
                    $taskEthnicity->completed = 0;
                    $taskEthnicity->status = 1;
                    $taskEthnicity->save();

                }
            }
        }

        if ($isCareer) {
            $careers = $request['careerArray'];

            if ($careers != null) {
                foreach ($careers as $career) {
                    $taskCareer = new TaskCareer();
                    $taskCareer->idtask = $task->idtask;
                    $taskCareer->idcareer = $career['id'];
                    $taskCareer->value = $career['value'];
                    $taskCareer->completed = 0;
                    $taskCareer->status = 1;
                    $taskCareer->save();


                }
            }
        }

        if ($isReligion) {
            $religions = $request['religionArray'];
            if ($religions != null) {
                foreach ($religions as $religion) {
                    $taskReligion = new TaskReligion();
                    $taskReligion->idtask = $task->idtask;
                    $taskReligion->idreligion = $religion['id'];
                    $taskReligion->value = $religion['value'];
                    $taskReligion->completed = 0;
                    $taskReligion->status = 1;
                    $taskReligion->save();
                }
            }
        }

        if ($isEducational) {
            $educations = $request['educationArray'];
            if ($educations != null) {
                foreach ($educations as $education) {
                    $taskEducation = new TaskEducation();
                    $taskEducation->idtask = $task->idtask;
                    $taskEducation->ideducational_qualification = $education['id'];
                    $taskEducation->value = $education['value'];
                    $taskEducation->completed = 0;
                    $taskEducation->status = 1;
                    $taskEducation->save();
                }
            }
        }

        if ($isIncome) {
            $incomes = $request['incomeArray'];
            if ($incomes != null) {
                foreach ($incomes as $income) {
                    $taskIncome = new TaskIncome();
                    $taskIncome->idtask = $task->idtask;
                    $taskIncome->idnature_of_income = $income['id'];
                    $taskIncome->value = $income['value'];
                    $taskIncome->completed = 0;
                    $taskIncome->status = 1;
                    $taskIncome->save();
                }
            }
        }

        if ($isGender) {
            $genders = $request['genderArray'];
            if ($genders != null) {
                foreach ($genders as $gender) {
                    $taskIncome = new TaskGender();
                    $taskIncome->idtask = $task->idtask;
                    $taskIncome->gender = $gender['id'];
                    $taskIncome->value = $gender['value'];
                    $taskIncome->completed = 0;
                    $taskIncome->status = 1;
                    $taskIncome->save();
                }
            }
        }

        if ($isJobSector) {
            $jobs = $request['jobSectorArray'];
            if ($jobs != null) {
                foreach ($jobs as $job) {
                    $taskJob = new TaskJobSector();
                    $taskJob->idtask = $task->idtask;
                    $taskJob->job_sector = $job['id'];
                    $taskJob->value = $job['value'];
                    $taskJob->completed = 0;
                    $taskJob->status = 1;
                    $taskJob->save();
                }
            }
        }

        if ($isBranch) {
            $branches = $request['branchArray'];
            if ($branches != null) {
                foreach ($branches as $branche) {
                    $taskJob = new TaskBranchSociety();
                    $taskJob->idtask = $task->idtask;
                    $taskJob->idposition = $branche['id'];
                    $taskJob->value = $branche['value'];
                    $taskJob->completed = 0;
                    $taskJob->status = 1;
                    $taskJob->save();
                }
            }
        }

        if ($isWomens) {
            $womens = $request['womenArray'];
            if ($womens != null) {
                foreach ($womens as $women) {
                    $taskJob = new TaskWomens();
                    $taskJob->idtask = $task->idtask;
                    $taskJob->idposition = $women['id'];
                    $taskJob->value = $women['value'];
                    $taskJob->completed = 0;
                    $taskJob->status = 1;
                    $taskJob->save();
                }
            }
        }

        if ($isYouth) {
            $youths = $request['youthArray'];
            if ($youths != null) {
                foreach ($youths as $youth) {
                    $taskJob = new TaskYouth();
                    $taskJob->idtask = $task->idtask;
                    $taskJob->idposition = $youth['id'];
                    $taskJob->value = $youth['value'];
                    $taskJob->completed = 0;
                    $taskJob->status = 1;
                    $taskJob->save();
                }
            }
        }

        //save in community tables end
        return response()->json(['success' => 'Default task saved successfully']);

    }

//    public function view(Request $request)
//    {
//        $searchCol = $request['searchCol'];
//        $searchText = $request['searchText'];
//        $endDate = $request['end'];
//        $startDate = $request['start'];
//
//        $query = Task::query();
//        if (!empty($searchText)) {
//            if ($searchCol == 1) {
//                $query = $query->whereHas('user', function ($q) use ($searchText) {
//                    $q->where('fName', 'like', '%' . $searchText . '%');
//                });
//            } else if ($searchCol == 2) {
//                $query = $query->whereHas('user', function ($q) use ($searchText) {
//                    $q->where('lName', 'like', '%' . $searchText . '%');
//                });
//            }
//        }
//        if (!empty($startDate) && !empty($endDate)) {
//            $startDate = date('Y-m-d', strtotime($request['start']));
//            $endDate = date('Y-m-d', strtotime($request['end'] . ' +1 day'));
//
//            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
//        }
//        $tasks = $query->where('assigned_by', Auth::user()->idUser)->where('isDefault', 0)->latest()->paginate(10);
//
//        return view('task.view_tasks', ['title' => __('View Budget'), 'tasks' => $tasks]);
//    }

    public function getById(Request $request)
    {
        $id = $request['id'];
        if ($id != null) {
            $task = Task::with(['ethnicities', 'ethnicities.ethnicity', 'careers', 'careers.career', 'religions', 'religions.religion', 'incomes', 'incomes.income', 'educations', 'educations.education', 'age'])->where('assigned_by', Auth::user()->idUser)->where('idtask', $id)->first();
            if ($task != null) {
                return response()->json(['success' => $task]);
            } else {
                return response()->json(['errors' => ['error' => 'Invalid task.']]);
            }
        } else {
            return response()->json(['errors' => ['error' => 'Invalid task.']]);
        }
    }

    public function store(Request $request)
    {
        $validationMessages = [
            'userId.required' => 'Invalid agent!',
            'userId.numeric' => 'Invalid agent!',
            'members.required' => 'Number of members should be provided!',
            'members.not_in' => 'Number of members should be grater than zero (0)!',
            'ageComparison.required' => 'Age invalid.Please refresh page!',
            'ageComparison.numeric' => 'Age invalid.Please refresh page!',
            'minAge.numeric' => 'Age should be numeric!',
            'maxAge.numeric' => 'Age should be numeric!',
            'gender.numeric' => 'Invalid gender.please refresh page!',
            'jobSector.numeric' => 'Invalid job sector.please refresh page!',
            'gender.required' => 'Gender should be provided!',
            'jobSector.required' => 'Job sector should be provided!',
        ];

        $validator = \Validator::make($request->all(), [
            'userId' => 'required|numeric',
            'members' => 'required|not_in:0',
            'ageComparison' => 'required|numeric',
            'minAge' => 'nullable|numeric',
            'maxAge' => 'nullable|numeric',
            'religions.*' => 'nullable|',
            'ethnicities.*' => 'nullable|',
            'incomes.*' => 'nullable|',
            'educations.*' => 'nullable|',
            'careers.*' => 'nullable|',
            'gender' => 'required|numeric',
            'jobSector' => 'required|numeric',

        ], $validationMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        if ($request['minAge'] >= $request['maxAge'] && $request['ageComparison'] == 3) {
            return response()->json(['errors' => ['error' => 'Min Age can not be equal or grater than max age!']]);
        }

        $isDefault = $request['isDefault'] == 1 ? 1 : 0;
        if ($isDefault) {
            $userId = null;
            $status = 3;
            $isExist = Task::where('idoffice', Auth::user()->idoffice)->where('isDefault', 1)->first();
            if ($isExist != null) {
                $isExist->ethnicities()->delete();
                $isExist->careers()->delete();
                $isExist->incomes()->delete();
                $isExist->religions()->delete();
                $isExist->educations()->delete();
                $isExist->age()->delete();
                $isExist->delete();
            }
        } else {
            $userId = $request['userId'];
            $status = 2;// pending

        }
        //Validation end


        //save in task table
        $task = new Task();
        $task->idUser = $userId;
        $task->assigned_by = Auth::user()->idUser;
        $task->idoffice = Auth::user()->idoffice;
        $task->task_no = $task->getNextNo();
        $task->isDefault = $isDefault;
        $task->target = intval($request['members']);
        $task->task_gender = $request['gender'];
        $task->task_job_sector = intval($request['jobSector']);
        $task->task_job_sector = intval($request['jobSector']);
        $task->completed_amount = 0;
        $task->description = $request['description'];
        $task->status = $status;
        $task->save();
        //save in task table end

        //save in community tables
        if ($request['minAge'] != null) {
            $taskAge = new TaskAge();
            $taskAge->idtask = $task->idtask;
            $taskAge->comparison = $request['ageComparison'];
            $taskAge->minAge = $request['minAge'];
            $taskAge->maxAge = $request['maxAge'];
            $taskAge->status = 1;
            $taskAge->save();
        }

        $careers = $request['careers'];
        if ($careers != null) {
            foreach ($careers as $career) {
                $taskCareer = new TaskCareer();
                $taskCareer->idtask = $task->idtask;
                $taskCareer->idcareer = $career;
                $taskCareer->status = 1;
                $taskCareer->save();

            }
        }

        $religions = $request['religions'];
        if ($religions != null) {
            foreach ($religions as $religion) {
                $taskCareer = new TaskReligion();
                $taskCareer->idtask = $task->idtask;
                $taskCareer->idreligion = $religion;
                $taskCareer->status = 1;
                $taskCareer->save();

            }
        }

        $ethnicities = $request['ethnicities'];
        if ($ethnicities != null) {
            foreach ($ethnicities as $ethnicity) {
                $taskCareer = new TaskEthnicity();
                $taskCareer->idtask = $task->idtask;
                $taskCareer->idethnicity = $ethnicity;
                $taskCareer->status = 1;
                $taskCareer->save();

            }
        }

        $educations = $request['educations'];
        if ($educations != null) {
            foreach ($educations as $education) {
                $taskCareer = new TaskEducation();
                $taskCareer->idtask = $task->idtask;
                $taskCareer->ideducational_qualification = $education;
                $taskCareer->status = 1;
                $taskCareer->save();

            }
        }

        $incomes = $request['incomes'];
        if ($incomes != null) {
            foreach ($incomes as $income) {
                $taskCareer = new TaskIncome();
                $taskCareer->idtask = $task->idtask;
                $taskCareer->idnature_of_income = $income;
                $taskCareer->status = 1;
                $taskCareer->save();

            }
        }
        //save in community tables end
        return response()->json(['success' => 'dfd']);

    }

    public function deactivate(Request $request)
    {
        $id = $request['id'];
        $task = Task::find(intval($id));
        if ($task != null) {
            if ($task->status == 1) {
                $task->status = 0;
                $task->save();
            }

            return response()->json(['success' => 'Task deactivated!']);
        } else {
            return response()->json(['errors' => ['error' => 'Task invalid!']]);

        }
    }

    public function activate(Request $request)
    {
        $id = $request['id'];
        $task = Task::find(intval($id));
        if ($task != null) {
            if ($task->status == 0) {
                $task->status = 1;
                $task->save();
            }

            return response()->json(['success' => 'Task activated!']);
        } else {
            return response()->json(['errors' => ['error' => 'Task invalid!']]);

        }
    }

    public function createDefault()
    {
        $default = Task::with(['age', 'educations.education', 'religions.religion', 'incomes.income', 'careers.career', 'ethnicities.ethnicity'])->where('idoffice', Auth::user()->idoffice)->where('isDefault', 1)->first();
        $careers = Career::where('status', 1)->get();
        $religions = Religion::where('status', 1)->get();
        $incomes = NatureOfIncome::where('status', 1)->get();
        $educations = EducationalQualification::where('status', 1)->get();
        $ethnicities = Ethnicity::where('status', 1)->get();
        $positions = Position::where('status', 1)->get();
        $taskTypes = TaskTypes::where('status', 1)->get();
        return view('task.default')->with(['taskTypes' => $taskTypes, 'positions' => $positions, 'default' => $default, 'title' => 'Default Estimation', 'careers' => $careers, 'religions' => $religions, 'incomes' => $incomes, 'educations' => $educations, 'ethnicities' => $ethnicities]);

    }

    public function assignTask($request)
    {
        $user = User::find(intval($request['id']));
        $type = $request['type'];

        $taskUser = TaskTypeUser::where('idUser', $user->idUser)->first();
        if ($taskUser == null) {
            $taskUser = new TaskTypeUser();
            $taskUser->idtask_type = $type;
            $taskUser->idUser = $user->idUser;
            $taskUser->assigned_by = Auth::user()->idUser;
            $taskUser->status = 1;
            $taskUser->save();
        } else {
            $taskUser->idtask_type = $type;
            $taskUser->status = 1;
            $taskUser->save();
        }


    }

    public function assignDefaultTask($agentId)
    {
        $user = User::find(intval($agentId));
        $type = 1;
        $taskUser = TaskTypeUser::where('idUser', $user->idUser)->first();
        if ($taskUser == null) {

            $officeAdmin  = User::where('idoffice',$user->idoffice)->where('iduser_role',3)->first()->idUser;

            $taskUser = new TaskTypeUser();
            $taskUser->idtask_type = $type;
            $taskUser->idUser = $user->idUser;
            $taskUser->assigned_by = $officeAdmin;
            $taskUser->status = 1;
            $taskUser->save();
        } else {
            $taskUser->idtask_type = $type;
            $taskUser->status = 1;
            $taskUser->save();
        }


    }

    public function calAgentBudget($user, $totalVoters)
    {
        $budgetTYpe = TaskTypeUser::where('idUser', $user->idUser)->first();
        if ($budgetTYpe != null) {
            $defaultTask = Task::where('idoffice', $user->idoffice)->where('isDefault', 1)->where('idtask_type', $budgetTYpe->idtask_type)->first();

            if ($defaultTask != null) {

                $totalBudget = floor(($totalVoters * $defaultTask->target) / 100);
                $task = Task::where('idoffice', Auth::user()->idoffice)->where('idUser', $user->idUser)->first();

                //save in task table
                if ($task == null) {
                    $task = new Task();
                    $task->completed_amount = 0;
                }
                $task->idUser = $user->idUser;
                $task->assigned_by = $budgetTYpe->assigned_by;
                $task->idoffice = Auth::user()->idoffice;
                $task->idtask_type = $budgetTYpe->idtask_type;
                $task->isDefault = 0;
                $task->ethnicity = $defaultTask->ethnicity;
                $task->religion = $defaultTask->religion;
                $task->career = $defaultTask->career;
                $task->income = $defaultTask->income;
                $task->education = $defaultTask->education;
                $task->job_sector = $defaultTask->job_sector;
                $task->gender = $defaultTask->gender;
                $task->branch = $defaultTask->branch;
                $task->womens = $defaultTask->womens;
                $task->youth = $defaultTask->youth;
                $task->target = $totalBudget;
                $task->status = 1;
                $task->save();
                //save in task table end

                if ($defaultTask->ethnicity != null) {
                    $ethnicities = $defaultTask->ethnicities;
                    if ($ethnicities != null) {
                        foreach ($ethnicities as $ethnicity) {
                            $taskEthnicity = TaskEthnicity::where('idtask', $task->idtask)->where('idethnicity', $ethnicity->idethnicity)->first();
                            if ($taskEthnicity == null) {
                                $taskEthnicity = new TaskEthnicity();
                                $taskEthnicity->completed = 0;
                            }
                            $taskEthnicity->idtask = $task->idtask;
                            $taskEthnicity->idethnicity = $ethnicity->idethnicity;
                            $taskEthnicity->value = floor(($totalBudget * $ethnicity->value) / 100);
                            $taskEthnicity->status = 1;
                            $taskEthnicity->save();

                        }
                    }
                }

                if ($defaultTask->career) {
                    $careers = $defaultTask->careers;

                    if ($careers != null) {
                        foreach ($careers as $career) {
                            $taskCareer = TaskCareer::where('idtask', $task->idtask)->where('idcareer', $career->idcareer)->first();
                            if ($taskCareer == null) {
                                $taskCareer = new TaskCareer();
                                $taskCareer->completed = 0;
                            }

                            $taskCareer->idtask = $task->idtask;
                            $taskCareer->idcareer = $career->idcareer;
                            $taskCareer->value = floor(($totalBudget * $career->value) / 100);
                            $taskCareer->status = 1;
                            $taskCareer->save();
                        }
                    }
                }

                if ($defaultTask->religion) {
                    $religions = $defaultTask->religions;
                    if ($religions != null) {
                        foreach ($religions as $religion) {
                            $taskReligion = TaskReligion::where('idtask', $task->idtask)->where('idreligion', $religion->idreligion)->first();
                            if ($taskReligion == null) {
                                $taskReligion = new TaskReligion();
                                $taskReligion->completed = 0;
                            }
                            $taskReligion->idtask = $task->idtask;
                            $taskReligion->idreligion = $religion->idreligion;
                            $taskReligion->value = floor(($totalBudget * $religion->value) / 100);
                            $taskReligion->status = 1;
                            $taskReligion->save();
                        }
                    }
                }

                if ($defaultTask->education) {
                    $educations = $defaultTask->educations;
                    if ($educations != null) {
                        foreach ($educations as $education) {
                            $taskEducation = TaskEducation::where('idtask', $task->idtask)->where('ideducational_qualification', $education->ideducational_qualification)->first();
                            if ($taskEducation == null) {
                                $taskEducation = new TaskEducation();
                                $taskEducation->completed = 0;
                            }
                            $taskEducation->idtask = $task->idtask;
                            $taskEducation->ideducational_qualification = $education->ideducational_qualification;
                            $taskEducation->value = floor(($totalBudget * $education->value) / 100);
                            $taskEducation->status = 1;
                            $taskEducation->save();
                        }
                    }
                }

                if ($defaultTask->income) {
                    $incomes = $defaultTask->incomes;
                    if ($incomes != null) {
                        foreach ($incomes as $income) {
                            $taskIncome = TaskIncome::where('idtask', $task->idtask)->where('idnature_of_income', $income->idnature_of_income)->first();
                            if ($taskIncome == null) {
                                $taskIncome = new TaskIncome();
                                $taskIncome->completed = 0;
                            }
                            $taskIncome->idtask = $task->idtask;
                            $taskIncome->idnature_of_income = $income->idnature_of_income;
                            $taskIncome->value = floor(($totalBudget * $income->value) / 100);
                            $taskIncome->status = 1;
                            $taskIncome->save();
                        }
                    }
                }

                if ($defaultTask->gender) {
                    $genders = $defaultTask->genders;
                    if ($genders != null) {
                        foreach ($genders as $gender) {
                            $taskGender = TaskGender::where('idtask', $task->idtask)->where('gender', $gender->gender)->first();
                            if ($taskGender == null) {
                                $taskGender = new TaskGender();
                                $taskGender->completed = 0;
                            }
                            $taskGender->idtask = $task->idtask;
                            $taskGender->gender = $gender->gender;
                            $taskGender->value = floor(($totalBudget * $gender->value) / 100);
                            $taskGender->status = 1;
                            $taskGender->save();
                        }
                    }
                }

                if ($defaultTask->job_sector) {
                    $jobs = $defaultTask->job;
                    if ($jobs != null) {
                        foreach ($jobs as $job) {
                            $taskJob = TaskJobSector::where('idtask', $task->idtask)->where('job_sector', $job->job_sector)->first();
                            if ($taskJob == null) {
                                $taskJob = new TaskJobSector();
                                $taskJob->completed = 0;
                            }
                            $taskJob->idtask = $task->idtask;
                            $taskJob->job_sector = $job->job_sector;
                            $taskJob->value = floor(($totalBudget * $job->value) / 100);
                            $taskJob->status = 1;
                            $taskJob->save();
                        }
                    }
                }

                if ($defaultTask->branch) {
                    $branches = $defaultTask->branchSociety;
                    if ($branches != null) {
                        foreach ($branches as $branche) {
                            $taskBranch = TaskBranchSociety::where('idtask', $task->idtask)->where('idposition', $branche->idposition)->first();
                            if ($taskBranch == null) {
                                $taskBranch = new TaskBranchSociety();
                                $taskBranch->completed = 0;
                            }
                            $taskBranch->idtask = $task->idtask;
                            $taskBranch->idposition = $branche->idposition;
                            $taskBranch->value = floor(($totalBudget * $branche->value) / 100);
                            $taskBranch->completed = 0;
                            $taskBranch->status = 1;
                            $taskBranch->save();
                        }
                    }
                }

                if ($defaultTask->womens) {
                    $womens = $defaultTask->womensSociety;
                    if ($womens != null) {
                        foreach ($womens as $women) {
                            $taskWomen = TaskWomens::where('idtask', $task->idtask)->where('idposition', $women->idposition)->first();
                            if ($taskWomen == null) {
                                $taskWomen = new TaskWomens();
                                $taskWomen->completed = 0;
                            }
                            $taskWomen->idtask = $task->idtask;
                            $taskWomen->idposition = $women->idposition;
                            $taskWomen->value = floor(($totalBudget * $women->value) / 100);
                            $taskWomen->status = 1;
                            $taskWomen->save();
                        }
                    }
                }

                if ($defaultTask->youth) {
                    $youths = $defaultTask->youthSociety;
                    if ($youths != null) {
                        foreach ($youths as $youth) {
                            $taskYouth = TaskYouth::where('idtask', $task->idtask)->where('idposition', $youth->idposition)->first();
                            if ($taskYouth == null) {
                                $taskYouth = new TaskYouth();
                                $taskYouth->completed = 0;
                            }
                            $taskYouth->idtask = $task->idtask;
                            $taskYouth->idposition = $youth->idposition;
                            $taskYouth->value = floor(($totalBudget * $youth->value) / 100);
                            $taskYouth->completed = 0;
                            $taskYouth->status = 1;
                            $taskYouth->save();
                        }
                    }
                }
            }
        }
    }

    public function updateTask($memberId, $id)
    {
        $tasks = Task::where('idUser', $id)->where('status', 2)->get();
        $member = User::find($memberId);
        foreach ($tasks as $task) {
            if ($task->task_gender != 0) {
                if ($member->gender != $task->task_gender) {
                    continue;
                }
            }

            if ($task->task_job_sector != 0) {
                if ($member->member->is_government != $task->task_job_sector) {
                    continue;
                }
            }

            if ($task->religions != null) {
                if (!$task->religions->contains('idreligion', $member->member->idreligion)) {
                    continue;
                }
            }

            if ($task->ethnicities != null) {
                if (!$task->ethnicities->contains('idethnicity', $member->member->idethnicity)) {
                    continue;
                }
            }
//
//            if ($task->careers != null) {
//                if (!$task->careers->contains('idcareer', $member->member->idcareer)) {
//                    continue;
//                }
//            }

//            if ($task->incomes != null) {
//                if (!$task->careers->contains('idnature_of_income', $member->member->idnature_of_income)) {
//                    continue;
//                }
//            }
//
//            if ($task->educations != null) {
//                if (!$task->careers->contains('ideducational_qualification', $member->member->ideducational_qualification)) {
//                    continue;
//                }
//            }
//
//            if ($task->age != null) {
//                if ($task->age->comparison == 0 && $task->age->minAge != $member->user->age) {
//
//                    continue;
//                }
//                if ($task->age->comparison == 1 && $task->age->minAge <= $member->user->age) {
//
//                    continue;
//                }
//                if ($task->age->comparison == 2 && $task->age->minAge >= $member->user->age) {
//
//                    continue;
//                }
//                if ($task->age->comparison == 3 && ($task->age->minAge >= $member->user->age || $task->age->maxAge <= $member->user->age)) {
//
//                    continue;
//                }
//            }

//            $task->completed_amount += 1;
//            $task->save();
//            $this->isComplete($task->idtask);
//            break;
        }
    }

    public function isComplete($id)
    {
        $task = Task::find(intval($id));
        if ($task->target <= $task->completed_amount) {
            $task->status = 1;
            $task->save();
        }
    }

    public function complete($memberId, $agentId){
        $agentUSer = User::find(intval($agentId));
        $memberUser = User::find(intval($memberId));
        $member = Member::where('idUser',$memberId)->first();

        $task = Task::where('idUser',$agentId)->where('status',1)->latest()->first();

        if($task != null) {
            //Increment completed member count without considering prameters
            $task->completed_amount += 1;
            $task->save();

            //search ethnicity match
            if ($task->ethnicity == 1) {
                $array = TaskEthnicity::where('idtask', $task->idtask)->get();
                if ($array != null) {
                    foreach ($array as $arr) {
                        if ($arr->idethnicity == $member->idethnicity) {
                            $arr->completed += 1;
                            $arr->save();
                            break;
                        }
                    }
                }
            }

            //search religion match
            if ($task->religion == 1) {
                $array = TaskReligion::where('idtask', $task->idtask)->get();
                if ($array != null) {
                    foreach ($array as $arr) {
                        if ($arr->idreligion == $member->idreligion) {
                            $arr->completed += 1;
                            $arr->save();
                            break;
                        }
                    }
                }
            }

            //search education match
            if ($task->education == 1) {
                $array = TaskEducation::where('idtask', $task->idtask)->get();
                if ($array != null) {
                    foreach ($array as $arr) {
                        if ($arr->ideducational_qualification == $member->ideducational_qualification) {
                            $arr->completed += 1;
                            $arr->save();
                            break;
                        }
                    }
                }
            }

            //search career match
            if ($task->career == 1) {
                $array = TaskCareer::where('idtask', $task->idtask)->get();
                if ($array != null) {
                    foreach ($array as $arr) {
                        if ($arr->idcareer == $member->idcareer) {
                            $arr->completed += 1;
                            $arr->save();
                            break;
                        }
                    }
                }
            }

            //search income match
            if ($task->income == 1) {
                $array = TaskIncome::where('idtask', $task->idtask)->get();
                if ($array != null) {
                    foreach ($array as $arr) {
                        if ($arr->idnature_of_income == $member->idnature_of_income) {
                            $arr->completed += 1;
                            $arr->save();
                            break;
                        }
                    }
                }
            }

            //search gender match
            if ($task->gender == 1) {
                $array = TaskGender::where('idtask', $task->idtask)->get();
                if ($array != null) {
                    foreach ($array as $arr) {
                        if ($arr->gender == $member->gender) {
                            $arr->completed += 1;
                            $arr->save();
                            break;
                        }
                    }
                }
            }

            //search job sector match
            if ($task->job_sector == 1) {
                $array = TaskJobSector::where('idtask', $task->idtask)->get();
                if ($array != null) {
                    foreach ($array as $arr) {
                        if ($arr->job_sector == $member->is_government) {
                            $arr->completed += 1;
                            $arr->save();
                            break;
                        }
                    }
                }
            }

            //search branch society match
            if ($task->branch == 1) {
                $array = TaskBranchSociety::where('idtask', $task->idtask)->get();
                $society = UserSociety::where('idUser', $memberId)->where('idsociety', 1)->first();
                if ($society != null) {
                    if ($array != null) {
                        foreach ($array as $arr) {
                            if ($arr->idposition == $society->idposition) {
                                $arr->completed += 1;
                                $arr->save();
                                break;
                            }
                        }
                    }
                }
            }

            //search women society match
            if ($task->branch == 1) {
                $array = TaskWomens::where('idtask', $task->idtask)->get();
                $society = UserSociety::where('idUser', $memberId)->where('idsociety', 2)->first();
                if ($society != null) {
                    if ($array != null) {
                        foreach ($array as $arr) {
                            if ($arr->idposition == $society->idposition) {
                                $arr->completed += 1;
                                $arr->save();
                                break;
                            }
                        }
                    }
                }
            }

            //search youth society match
            if ($task->branch == 1) {
                $array = TaskYouth::where('idtask', $task->idtask)->get();
                $society = UserSociety::where('idUser', $memberId)->where('idsociety', 3)->first();
                if ($society != null) {
                    if ($array != null) {
                        foreach ($array as $arr) {
                            if ($arr->idposition == $society->idposition) {
                                $arr->completed += 1;
                                $arr->save();
                                break;
                            }
                        }
                    }
                }
            }
        }
    }

    public function isDefaultBudgetCreated(){
        $defaultTask = Task::where('idoffice', Auth::user()->idoffice)->where('isDefault', 1)->where('idtask_type', 1)->first();
        if($defaultTask == null){
            return 'false';
        }
        return 'true';

    }

    public function view(Request $request){
        $searchCol = $request['searchCol'];
        $searchText = $request['searchText'];
        $endDate = $request['end'];
        $startDate = $request['start'];

        $query = Task::query();
        if (!empty($searchText)) {
            if ($searchCol == 1) {
                $query = $query->whereHas('user', function ($q) use ($searchText) {
                    $q->where('fName', 'like', '%' . $searchText . '%');
                });
            } else if ($searchCol == 2) {
                $query = $query->whereHas('user', function ($q) use ($searchText) {
                    $q->where('lName', 'like', '%' . $searchText . '%');
                });
            }
        }
        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end'] . ' +1 day'));

            $query = $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $tasks = $query->where('isDefault', 1)->where('idoffice', Auth::user()->idoffice)->latest()->paginate(10);
        $taskTypes = TaskTypes::where('status', 1)->get();

        return view('task.view_budget', ['title' => __('View Estimation'), 'tasks' => $tasks,'taskTypes'=>$taskTypes]);
    }

    public function viewByType(Request $request){
        $task =  Task::where('idoffice',Auth::user()->idoffice)->where('idtask_type',$request['taskType'])->where('status',1)->latest()->first();
        if($task != null) {
            if ($task->ethnicity == 1) {
                $ethnicities = $task->ethnicities;
                foreach ($ethnicities as $ethnicity) {
                    $ethnicity['label'] = $ethnicity->ethnicity->name_en;
                    $ethnicity['value'] = $ethnicity->value;

                    $ethnicity->makeHidden('created_at')->toArray();
                    $ethnicity->makeHidden('updated_at')->toArray();
                    $ethnicity->makeHidden('ethnicity')->toArray();
                    $ethnicity->makeHidden('completed')->toArray();
                    $ethnicity->makeHidden('status')->toArray();
                    $ethnicity->makeHidden('idethnicity')->toArray();
                    $ethnicity->makeHidden('idtask')->toArray();
                    $ethnicity->makeHidden('idtask_ethnicity')->toArray();
                }
            } else {
                $ethnicities = [];
            }

            if ($task->religion == 1) {
                $religions = $task->religions;
                foreach ($religions as $religion) {
                    $religion['label'] = $religion->religion->name_en;
                    $religion['value'] = $religion->value.'%';

                    $religion->makeHidden('created_at')->toArray();
                    $religion->makeHidden('updated_at')->toArray();
                    $religion->makeHidden('completed')->toArray();
                    $religion->makeHidden('status')->toArray();
                    $religion->makeHidden('idtask')->toArray();
                    $religion->makeHidden('idtask_religion')->toArray();
                    $religion->makeHidden('religion')->toArray();
                    $religion->makeHidden('idreligion')->toArray();

                }
            } else {
                $religions = [];
            }

            if ($task->income == 1) {
                $incomeArray = $task->incomes;
                foreach ($incomeArray as $income) {

                    $income['label'] = $income->income->name_en;
                    $income['value'] = $income->value.'%';

                    $income->makeHidden('created_at')->toArray();
                    $income->makeHidden('updated_at')->toArray();
                    $income->makeHidden('completed')->toArray();
                    $income->makeHidden('status')->toArray();
                    $income->makeHidden('idtask')->toArray();
                    $income->makeHidden('idnature_of_income')->toArray();
                    $income->makeHidden('income')->toArray();
                    $income->makeHidden('idtask_income')->toArray();

                }
            } else {
                $incomeArray = [];
            }

            if ($task->gender == 1) {
                $genderArray = $task->genders;
                foreach ($genderArray as $gender) {

                    if ($gender->gender == 1) {
                        $genderName = 'Male';
                    } else if ($gender->gender == 2) {
                        $genderName = 'Female';

                    } else if ($gender->gender == 3) {
                        $genderName = 'Other';

                    } else {
                        $genderName = 'Unknown';

                    }

                    $gender['label'] = $genderName;
                    $gender['value'] = $gender->value.'%';

                    $gender->makeHidden('created_at')->toArray();
                    $gender->makeHidden('updated_at')->toArray();
                    $gender->makeHidden('completed')->toArray();
                    $gender->makeHidden('status')->toArray();
                    $gender->makeHidden('idtask')->toArray();
                    $gender->makeHidden('gender')->toArray();
                    $gender->makeHidden('idtask_gender')->toArray();

                }
            } else {
                $genderArray = [];
            }


            if ($task->job_sector == 1) {
                $jobArray = $task->job;
                foreach ($jobArray as $job) {

                    if ($job->job_sector == 1) {
                        $jobName = 'Government';
                    } else if ($job->job_sector == 2) {
                        $jobName = 'Private';

                    } else if ($job->job_sector == 3) {
                        $jobName = 'Non-Government';

                    } else {
                        $jobName = 'Unknown';

                    }

                    $job['label'] = $jobName;
                    $job['value'] = $job->value.'%';

                    $job->makeHidden('created_at')->toArray();
                    $job->makeHidden('updated_at')->toArray();
                    $job->makeHidden('completed')->toArray();
                    $job->makeHidden('status')->toArray();
                    $job->makeHidden('job_sector')->toArray();
                    $job->makeHidden('idtask_job_sector')->toArray();

                }
            } else {
                $jobArray = [];
            }

            if ($task->education == 1) {
                $educationArray = $task->educations;
                foreach ($educationArray as $education) {

                    $education['label'] = $education->education->name_en;
                    $education['value'] = $education->value.'%';

                    $education->makeHidden('created_at')->toArray();
                    $education->makeHidden('updated_at')->toArray();
                    $education->makeHidden('completed')->toArray();
                    $education->makeHidden('status')->toArray();
                    $education->makeHidden('idtask')->toArray();
                    $education->makeHidden('idtask_education')->toArray();
                    $education->makeHidden('education')->toArray();
                    $education->makeHidden('ideducational_qualification')->toArray();


                }
            } else {
                $educationArray = [];
            }


            if ($task->career == 1) {
                $careersArray = $task->careers;
                foreach ($careersArray as $career) {

                    $career['label'] = $career->career->name_en;
                    $career['value'] = $career->value.'%';

                    $career->makeHidden('created_at')->toArray();
                    $career->makeHidden('updated_at')->toArray();
                    $career->makeHidden('completed')->toArray();
                    $career->makeHidden('status')->toArray();
                    $career->makeHidden('idtask')->toArray();
                    $career->makeHidden('idcareer')->toArray();
                    $career->makeHidden('career')->toArray();
                    $career->makeHidden('idtask_career')->toArray();


                }
            } else {
                $careersArray = [];
            }

            if ($task->branch == 1) {
                $branchArray = $task->branchSociety;
                foreach ($branchArray as $branch) {

                    $branch['label'] = $branch->position->name_en;
                    if($branch->idposition == 4){
                        $branch['value'] = $branch->value.'%';

                    }
                    else{
                        $branch['value'] = $branch->value;

                    }

                    $branch->makeHidden('created_at')->toArray();
                    $branch->makeHidden('updated_at')->toArray();
                    $branch->makeHidden('completed')->toArray();
                    $branch->makeHidden('status')->toArray();
                    $branch->makeHidden('idtask')->toArray();
                    $branch->makeHidden('idposition')->toArray();
                    $branch->makeHidden('position')->toArray();
                    $branch->makeHidden('idtask_branch_society')->toArray();


                }
            } else {
                $branchArray = [];
            }


            if ($task->womens == 1) {
                $womensArray = $task->womensSociety;
                foreach ($womensArray as $item) {

                    $item['label'] = $item->position->name_en;
                    if($item->idposition == 4){
                        $item['value'] = $item->value.'%';

                    }
                    else{
                        $item['value'] = $item->value;

                    }

                    $item->makeHidden('created_at')->toArray();
                    $item->makeHidden('updated_at')->toArray();
                    $item->makeHidden('completed')->toArray();
                    $item->makeHidden('status')->toArray();
                    $item->makeHidden('idtask')->toArray();
                    $item->makeHidden('idposition')->toArray();
                    $item->makeHidden('position')->toArray();
                    $item->makeHidden('task_women_society')->toArray();


                }
            } else {
                $womensArray = [];
            }

            if ($task->youth == 1) {
                $youthArray = $task->youthSociety;
                foreach ($youthArray as $item) {

                    $item['label'] = $item->position->name_en;
                    if($item->idposition == 4){
                        $item['value'] = $item->value.'%';

                    }
                    else{
                        $item['value'] = $item->value;

                    }

                    $item->makeHidden('created_at')->toArray();
                    $item->makeHidden('updated_at')->toArray();
                    $item->makeHidden('completed')->toArray();
                    $item->makeHidden('status')->toArray();
                    $item->makeHidden('idtask')->toArray();
                    $item->makeHidden('idposition')->toArray();
                    $item->makeHidden('position')->toArray();
                    $item->makeHidden('idtask_youth_society')->toArray();


                }
            } else {
                $youthArray = [];
            }

            $tasks['total'] = $task->target;
            $tasks['details'] = [
                ['title' => 'Ethnicity', 'data' => $ethnicities],
                ['title' => 'Religion', 'data' => $religions],
                ['title' => 'Income Status', 'data' => $incomeArray],
                ['title' => 'Gender', 'data' => $genderArray],
                ['title' => 'Educational Level', 'data' => $educationArray],
                ['title' => 'Career', 'data' => $careersArray],
                ['title' => 'Job Sector', 'data' => $jobArray],
                ['title' => 'Branch Society', 'data' => $branchArray],
                ['title' => 'Youth Society', 'data' => $youthArray],
                ['title' => 'Womens Society', 'data' => $womensArray],
            ];
        }
        else{
            $tasks['total'] = 0;
            $tasks['details'] = [ ];
        }
        return response()->json(['success' => $tasks]);

    }
}
