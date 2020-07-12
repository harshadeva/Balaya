<?php

namespace App\Http\Controllers\Api;

use App\Task;
use App\VotersCount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiTaskController extends Controller
{
    public function index(Request $request){
        if(Auth::user()->iduser_role != 6){
            return response()->json(['error' => 'You are not aa agent','statusCode'=>-99]);
        }
        if ($request['lang'] == 'si') {
            $lang = 'title_si';
            $langText = 'text_si';
        } elseif ($request['lang'] == 'ta') {
            $lang = 'title_ta';
            $langText = 'text_ta';
        } else {
            $lang = 'title_en';
            $langText = 'text_en';
        }
        $task = Task::where('idUser',Auth::user()->idUser)->latest()->first();
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
                    $religion['value'] = $religion->value;

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
                    $income['value'] = $income->value;

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
                    $gender['value'] = $gender->value;

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
                    $job['value'] = $job->value;

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
                    $education['value'] = $education->value;

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
                    $career['value'] = $career->value;

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
                    $branch['value'] = $branch->value;

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
                    $item['value'] = $item->value;

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
                    $item['value'] = $item->value;

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
        return response()->json(['success' => $tasks, 'statusCode' => 0]);
    }




}
