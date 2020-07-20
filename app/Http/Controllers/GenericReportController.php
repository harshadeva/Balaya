<?php

namespace App\Http\Controllers;

use App\Analysis;
use App\Career;
use App\Council;
use App\DivisionalSecretariat;
use App\EducationalQualification;
use App\ElectionDivision;
use App\Ethnicity;
use App\GramasewaDivision;
use App\NatureOfIncome;
use App\Post;
use App\PostResponse;
use App\Religion;
use App\User;
use App\VotersCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GenericReportController extends Controller
{
    public function agents(Request $request)
    {

        $electionDivisions = ElectionDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $secretariats = DivisionalSecretariat::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $councils = Council::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $careers = Career::where('status', 1)->get();
        $religions = Religion::where('status', 1)->get();
        $incomes = NatureOfIncome::where('status', 1)->get();
        $educations = EducationalQualification::where('status', 1)->get();
        $ethnicities = Ethnicity::where('status', 1)->get();

        $rows = $request['rows'] != null ? $request['rows'] == 'all' ? 100000 : $request['rows'] : 10;
        $query = User::query();

        $query = $query->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6);

        if ($request['gender'] != null) {
            $query = $query->where('gender', $request['gender']);
        }
        if (!empty($request['searchText'])) {

            if ($request['searchCol'] == 1) {
                $query = $query->where('fName', 'like', '%' . $request['searchText'] . '%');
            } else if ($request['searchCol'] == 2) {
                $query = $query->where('lName', 'like', '%' . $request['searchText'] . '%');
            } else if ($request['searchCol'] == 3) {
                $query = $query->where('nic', $request['searchText']);

            } else if ($request['searchCol'] == 4) {
                $query = $query->where('email', 'like', '%' . $request['searchText'] . '%');
            } else if ($request['searchCol'] == 5) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('referral_code', 'like', '%' . $request['searchText'] . '%');
                });
            }

        }
        if ($request['village'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['divisionalType'] == 1) {
            if ($request['pollingBooth'] != null) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('idpolling_booth', $request['pollingBooth']);
                });
            } else if ($request['electionDivision'] != null) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('idelection_division', $request['electionDivision']);
                });
            }
        } else if ($request['divisionalType'] == 2) {
            if ($request['divisionalSecretariat'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('iddivisional_secretariat', $request['divisionalSecretariat'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('agent', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        } else if ($request['divisionalType'] == 3) {
            if ($request['council'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('idcouncil', $request['council'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('agent', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        }


        if ($request['ethnicity'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idethnicity', $request['ethnicity']);
            });
        }
        if ($request['religion'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idreligion', $request['religion']);
            });
        }
        if ($request['income'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idnature_of_income', $request['income']);
            });
        }

        if ($request['education'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('ideducational_qualification', $request['education']);
            });
        }

        if ($request['career'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idcareer', $request['career']);
            });
        }
        if (!empty($request['start']) && !empty($request['end'])) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end']));

            $query = $query->whereBetween('bday', [$startDate, $endDate]);
        }
        if ($request['jobSector'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('is_government', $request['jobSector']);
            });
        }
        $users = $query->where('status','!=',3)->latest()->paginate($rows);

        $users->appends([
            'start' => $request['start'],
            'rows' => $request['rows'],
            'end' => $request['end'],
            'searchCol' => $request['searchCol'],
            'searchText' => $request['searchText'],
            'village' => $request['village'],
            'gramasewaDivision' => $request['gramasewaDivision'],
            'pollingBooth' => $request['pollingBooth'],
            'electionDivision' => $request['electionDivision'],
            'ethnicity' => $request['ethnicity'],
            'religion' => $request['religion'],
            'income' => $request['income'],
            'education' => $request['education'],
            'career' => $request['career'],
            'jobSector' => $request['jobSector']
        ]);

        return view('generic_reports.agents')->with(['ethnicities' => $ethnicities, 'educations' => $educations, 'incomes' => $incomes, 'religions' => $religions, 'careers' => $careers, 'users' => $users, 'title' => 'Reports : Agents', 'electionDivisions' => $electionDivisions, 'secretariats' => $secretariats, 'councils' => $councils]);

    }

    public function members(Request $request)
    {

        $electionDivisions = ElectionDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $secretariats = DivisionalSecretariat::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $councils = Council::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $careers = Career::where('status', 1)->get();
        $religions = Religion::where('status', 1)->get();
        $incomes = NatureOfIncome::where('status', 1)->get();
        $educations = EducationalQualification::where('status', 1)->get();
        $ethnicities = Ethnicity::where('status', 1)->get();

        $rows = $request['rows'] != null ? $request['rows'] == 'all' ? 100000 : $request['rows'] : 10;
        $query = User::query();

        $query = $query->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 7);

        $query = $query->where(function ($q) {
            $q->whereHas('member', function ($q) {
                $q->whereHas('memberAgents', function ($q) {
                    $q->where('idoffice', Auth::user()->idoffice);
                });
            });
        });

        if ($request['gender'] != null) {
            $query = $query->where('gender', $request['gender']);
        }
        if (!empty($request['searchText'])) {

            if ($request['searchCol'] == 1) {
                $query = $query->where('fName', 'like', '%' . $request['searchText'] . '%');
            } else if ($request['searchCol'] == 2) {
                $query = $query->where('lName', 'like', '%' . $request['searchText'] . '%');
            } else if ($request['searchCol'] == 3) {
                $query = $query->where('nic', $request['searchText']);
            } else if ($request['searchCol'] == 4) {
                $query = $query->where('email', 'like', '%' . $request['searchText'] . '%');
            }
        }

        if ($request['village'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['divisionalType'] == 1) {
            if ($request['pollingBooth'] != null) {
                $query = $query->whereHas('member', function ($q) use ($request) {
                    $q->where('idpolling_booth', $request['pollingBooth']);
                });
            } else if ($request['electionDivision'] != null) {
                $query = $query->whereHas('member', function ($q) use ($request) {
                    $q->where('idelection_division', $request['electionDivision']);
                });
            }
        } else if ($request['divisionalType'] == 2) {
            if ($request['divisionalSecretariat'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('iddivisional_secretariat', $request['divisionalSecretariat'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('member', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        } else if ($request['divisionalType'] == 3) {
            if ($request['council'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('idcouncil', $request['council'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('member', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        }


        if ($request['ethnicity'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idethnicity', $request['ethnicity']);
            });
        }
        if ($request['religion'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idreligion', $request['religion']);
            });
        }
        if ($request['income'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idnature_of_income', $request['income']);
            });
        }

        if ($request['education'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('ideducational_qualification', $request['education']);
            });
        }

        if ($request['career'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('idcareer', $request['career']);
            });
        }
        if (!empty($request['start']) && !empty($request['end'])) {
            $startDate = date('Y-m-d', strtotime($request['start']));
            $endDate = date('Y-m-d', strtotime($request['end']));

            $query = $query->whereBetween('bday', [$startDate, $endDate]);
        }
        if ($request['jobSector'] != null) {
            $query = $query->whereHas('member', function ($q) use ($request) {
                $q->where('is_government', $request['jobSector']);
            });
        }
        $users = $query->where('status','!=',3)->latest()->paginate($rows);

        $users->appends([
            'start' => $request['start'],
            'rows' => $request['rows'],
            'end' => $request['end'],
            'searchCol' => $request['searchCol'],
            'searchText' => $request['searchText'],
            'village' => $request['village'],
            'gramasewaDivision' => $request['gramasewaDivision'],
            'pollingBooth' => $request['pollingBooth'],
            'electionDivision' => $request['electionDivision'],
            'ethnicity' => $request['ethnicity'],
            'religion' => $request['religion'],
            'income' => $request['income'],
            'education' => $request['education'],
            'career' => $request['career'],
            'jobSector' => $request['jobSector']
        ]);

        return view('generic_reports.member')->with(['ethnicities' => $ethnicities, 'educations' => $educations, 'incomes' => $incomes, 'religions' => $religions, 'careers' => $careers, 'users' => $users, 'title' => 'Reports : Members', 'electionDivisions' => $electionDivisions, 'secretariats' => $secretariats, 'councils' => $councils]);

    }

    public function age()
    {
        $electionDivisions = ElectionDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $secretariats = DivisionalSecretariat::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $councils = Council::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        return view('generic_reports.age')->with(['electionDivisions' => $electionDivisions, 'title' => 'Report : Age','councils'=>$councils,'secretariats'=>$secretariats]);
    }

    public function ageChart(Request $request)
    {
        $value = $request['age'] != null ? $request['age'] : 30;
        $agentMin = 0;
        $agentMax = 0;
        $agentEqual = 0;
        $memberMin = 0;
        $memberMax = 0;
        $memberEqual = 0;
        $query = User::query();

        if ($request['village'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['divisionalType'] == 1) {
            if ($request['pollingBooth'] != null) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('idpolling_booth', $request['pollingBooth']);
                });
            } else if ($request['electionDivision'] != null) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('idelection_division', $request['electionDivision']);
                });
            }
        } else if ($request['divisionalType'] == 2) {
            if ($request['divisionalSecretariat'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('iddivisional_secretariat', $request['divisionalSecretariat'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('agent', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        } else if ($request['divisionalType'] == 3) {
            if ($request['council'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('idcouncil', $request['council'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('agent', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        }

        $agents = $query->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->where('status', 1)->get();
        if ($agents != null) {
            foreach ($agents as $agent) {
                if ($agent->age < $value) {
                    $agentMin += 1;
                } elseif ($agent->age == $value) {
                    $agentEqual += 1;
                } else {
                    $agentMax += 1;
                }
            }
        }
        $agentCount = count($agents);

        $query1 = User::query();
        if ($request['village'] != null) {
            $query1 = $query1->whereHas('member', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query1 = $query1->whereHas('member', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['divisionalType'] == 1) {
            if ($request['pollingBooth'] != null) {
                $query1 = $query1->whereHas('member', function ($q) use ($request) {
                    $q->where('idpolling_booth', $request['pollingBooth']);
                });
            } else if ($request['electionDivision'] != null) {
                $query1 = $query1->whereHas('member', function ($q) use ($request) {
                    $q->where('idelection_division', $request['electionDivision']);
                });
            }
        } else if ($request['divisionalType'] == 2) {
            if ($request['divisionalSecretariat'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('iddivisional_secretariat', $request['divisionalSecretariat'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query1 = $query1->whereHas('member', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        } else if ($request['divisionalType'] == 3) {
            if ($request['council'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('idcouncil', $request['council'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query1 = $query1->whereHas('member', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        }

        $members = $query1->where('iduser_role', 7)->whereHas('member', function ($q) {
            $q->whereHas('memberAgents', function ($q) {
                $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
            });
        })->get();

        if ($members != null) {
            foreach ($members as $member) {
                if ($member->age < $value) {
                    $memberMin += 1;
                } elseif ($member->age == $value) {
                    $memberEqual += 1;
                } else {
                    $memberMax += 1;
                }
            }
        }
        $membersCount = count($members);

        return response()->json(['success' => ['agent_count' => $agentCount, 'member_count' => $membersCount, 'member_equal' => intval($memberEqual), 'agent_equal' => intval($agentEqual), 'agent_min' => intval($agentMin), 'agent_max' => intval($agentMax), 'member_min' => intval($memberMin), 'member_max' => intval($memberMax)]]);

    }

    public function education()
    {
        $electionDivisions = ElectionDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $secretariats = DivisionalSecretariat::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $councils = Council::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        return view('generic_reports.education')->with(['electionDivisions' => $electionDivisions, 'title' => 'Report : Education Qualifications','secretariats'=>$secretariats,'councils'=>$councils]);
    }

    public function educationChart(Request $request)
    {

        $query = User::query();
        if ($request['village'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['divisionalType'] == 1) {
            if ($request['pollingBooth'] != null) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('idpolling_booth', $request['pollingBooth']);
                });
            } else if ($request['electionDivision'] != null) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('idelection_division', $request['electionDivision']);
                });
            }
        } else if ($request['divisionalType'] == 2) {
            if ($request['divisionalSecretariat'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('iddivisional_secretariat', $request['divisionalSecretariat'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('agent', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        } else if ($request['divisionalType'] == 3) {
            if ($request['council'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('idcouncil', $request['council'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('agent', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        }

        $agents = $query->with(['agent.educationalQualification'])->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->where('status', 1)->get();

        $agentsGroup = $agents->groupBy(['agent.ideducational_qualification']);
        $agentCount = count($agents);

        $query1 = User::query();
        if ($request['village'] != null) {
            $query1 = $query1->whereHas('member', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query1 = $query1->whereHas('member', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['divisionalType'] == 1) {
            if ($request['pollingBooth'] != null) {
                $query1 = $query1->whereHas('member', function ($q) use ($request) {
                    $q->where('idpolling_booth', $request['pollingBooth']);
                });
            } else if ($request['electionDivision'] != null) {
                $query1 = $query1->whereHas('member', function ($q) use ($request) {
                    $q->where('idelection_division', $request['electionDivision']);
                });
            }
        } else if ($request['divisionalType'] == 2) {
            if ($request['divisionalSecretariat'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('iddivisional_secretariat', $request['divisionalSecretariat'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query1 = $query1->whereHas('member', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        } else if ($request['divisionalType'] == 3) {
            if ($request['council'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('idcouncil', $request['council'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query1 = $query1->whereHas('member', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        }

        $members = $query1->with(['member.educationalQualification'])->where('iduser_role', 7)->whereHas('member', function ($q) {
            $q->whereHas('memberAgents', function ($q) {
                $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
            });
        })->get();

        $membersGroups = $members->groupBy(['member.ideducational_qualification']);

        $membersCount = count($members);

        return response()->json(['success' => ['agents' => $agentsGroup, 'members' => $membersGroups, 'agent_count' => $agentCount, 'member_count' => $membersCount]]);

    }

    public function income()
    {
        $electionDivisions = ElectionDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $secretariats = DivisionalSecretariat::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $councils = Council::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        return view('generic_reports.income')->with(['electionDivisions' => $electionDivisions, 'title' => 'Report : Nature of Income','secretariats'=>$secretariats,'councils'=>$councils]);
    }

    public function incomeChart(Request $request)
    {

        $query = User::query();
        if ($request['village'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['divisionalType'] == 1) {
            if ($request['pollingBooth'] != null) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('idpolling_booth', $request['pollingBooth']);
                });
            } else if ($request['electionDivision'] != null) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('idelection_division', $request['electionDivision']);
                });
            }
        } else if ($request['divisionalType'] == 2) {
            if ($request['divisionalSecretariat'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('iddivisional_secretariat', $request['divisionalSecretariat'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('agent', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        } else if ($request['divisionalType'] == 3) {
            if ($request['council'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('idcouncil', $request['council'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('agent', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        }
        $agents = $query->with(['agent.natureOfIncome'])->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->where('status', 1)->get();

        $agentsGroup = $agents->groupBy(['agent.idnature_of_income']);
        $agentCount = count($agents);

        $query1 = User::query();
        if ($request['village'] != null) {
            $query1 = $query1->whereHas('member', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query1 = $query1->whereHas('member', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['divisionalType'] == 1) {
            if ($request['pollingBooth'] != null) {
                $query1 = $query1->whereHas('member', function ($q) use ($request) {
                    $q->where('idpolling_booth', $request['pollingBooth']);
                });
            } else if ($request['electionDivision'] != null) {
                $query1 = $query1->whereHas('member', function ($q) use ($request) {
                    $q->where('idelection_division', $request['electionDivision']);
                });
            }
        } else if ($request['divisionalType'] == 2) {
            if ($request['divisionalSecretariat'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('iddivisional_secretariat', $request['divisionalSecretariat'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query1 = $query1->whereHas('member', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        } else if ($request['divisionalType'] == 3) {
            if ($request['council'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('idcouncil', $request['council'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query1 = $query1->whereHas('member', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        }

        $members = $query1->with(['member.natureOfIncome'])->where('iduser_role', 7)->whereHas('member', function ($q) {
            $q->whereHas('memberAgents', function ($q) {
                $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
            });
        })->get();

        $membersGroups = $members->groupBy(['member.idnature_of_income']);

        $membersCount = count($members);

        return response()->json(['success' => ['agents' => $agentsGroup, 'members' => $membersGroups, 'agent_count' => $agentCount, 'member_count' => $membersCount]]);

    }

    public function career()
    {
        $electionDivisions = ElectionDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $secretariats = DivisionalSecretariat::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $councils = Council::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        return view('generic_reports.career')->with(['electionDivisions' => $electionDivisions, 'title' => 'Report : Career Type','secretariats'=>$secretariats,'councils'=>$councils]);
    }

    public function careerChart(Request $request)
    {

        $query = User::query();
        if ($request['village'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['divisionalType'] == 1) {
            if ($request['pollingBooth'] != null) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('idpolling_booth', $request['pollingBooth']);
                });
            } else if ($request['electionDivision'] != null) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('idelection_division', $request['electionDivision']);
                });
            }
        } else if ($request['divisionalType'] == 2) {
            if ($request['divisionalSecretariat'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('iddivisional_secretariat', $request['divisionalSecretariat'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('agent', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        } else if ($request['divisionalType'] == 3) {
            if ($request['council'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('idcouncil', $request['council'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('agent', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        }

        $agents = $query->with(['agent.career'])->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->where('status', 1)->get();

        $agentsGroup = $agents->groupBy(['agent.idcareer']);
        $agentCount = count($agents);

        $query1 = User::query();
        if ($request['village'] != null) {
            $query1 = $query1->whereHas('member', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query1 = $query1->whereHas('member', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['divisionalType'] == 1) {
            if ($request['pollingBooth'] != null) {
                $query1 = $query1->whereHas('member', function ($q) use ($request) {
                    $q->where('idpolling_booth', $request['pollingBooth']);
                });
            } else if ($request['electionDivision'] != null) {
                $query1 = $query1->whereHas('member', function ($q) use ($request) {
                    $q->where('idelection_division', $request['electionDivision']);
                });
            }
        } else if ($request['divisionalType'] == 2) {
            if ($request['divisionalSecretariat'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('iddivisional_secretariat', $request['divisionalSecretariat'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query1 = $query1->whereHas('member', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        } else if ($request['divisionalType'] == 3) {
            if ($request['council'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('idcouncil', $request['council'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query1 = $query1->whereHas('member', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        }

        $members = $query1->with(['member.career'])->where('iduser_role', 7)->whereHas('member', function ($q) {
            $q->whereHas('memberAgents', function ($q) {
                $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
            });
        })->get();

        $membersGroups = $members->groupBy(['member.idcareer']);

        $membersCount = count($members);

        return response()->json(['success' => ['agents' => $agentsGroup, 'members' => $membersGroups, 'agent_count' => $agentCount, 'member_count' => $membersCount]]);

    }

    public function religion()
    {
        $electionDivisions = ElectionDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $secretariats = DivisionalSecretariat::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $councils = Council::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        return view('generic_reports.religion')->with(['electionDivisions' => $electionDivisions, 'title' => 'Report : Religion','secretariats'=>$secretariats,'councils'=>$councils]);
    }

    public function religionChart(Request $request)
    {

        $query = User::query();
        if ($request['village'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['divisionalType'] == 1) {
            if ($request['pollingBooth'] != null) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('idpolling_booth', $request['pollingBooth']);
                });
            } else if ($request['electionDivision'] != null) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('idelection_division', $request['electionDivision']);
                });
            }
        } else if ($request['divisionalType'] == 2) {
            if ($request['divisionalSecretariat'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('iddivisional_secretariat', $request['divisionalSecretariat'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('agent', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        } else if ($request['divisionalType'] == 3) {
            if ($request['council'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('idcouncil', $request['council'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('agent', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        }

        $agents = $query->with(['agent.religion'])->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->where('status', 1)->get();

        $agentsGroup = $agents->groupBy(['agent.idreligion']);
        $agentCount = count($agents);

        $query1 = User::query();
        if ($request['village'] != null) {
            $query1 = $query1->whereHas('member', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query1 = $query1->whereHas('member', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['divisionalType'] == 1) {
            if ($request['pollingBooth'] != null) {
                $query1 = $query1->whereHas('member', function ($q) use ($request) {
                    $q->where('idpolling_booth', $request['pollingBooth']);
                });
            } else if ($request['electionDivision'] != null) {
                $query1 = $query1->whereHas('member', function ($q) use ($request) {
                    $q->where('idelection_division', $request['electionDivision']);
                });
            }
        } else if ($request['divisionalType'] == 2) {
            if ($request['divisionalSecretariat'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('iddivisional_secretariat', $request['divisionalSecretariat'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query1 = $query1->whereHas('member', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        } else if ($request['divisionalType'] == 3) {
            if ($request['council'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('idcouncil', $request['council'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query1 = $query1->whereHas('member', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        }

        $members = $query1->with(['member.religion'])->where('iduser_role', 7)->whereHas('member', function ($q) {
            $q->whereHas('memberAgents', function ($q) {
                $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
            });
        })->get();

        $membersGroups = $members->groupBy(['member.idreligion']);

        $membersCount = count($members);

        return response()->json(['success' => ['agents' => $agentsGroup, 'members' => $membersGroups, 'agent_count' => $agentCount, 'member_count' => $membersCount]]);

    }

    public function ethnicity()
    {
        $electionDivisions = ElectionDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $secretariats = DivisionalSecretariat::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $councils = Council::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        return view('generic_reports.ethnicity')->with(['electionDivisions' => $electionDivisions, 'title' => 'Report : Ethnicity','secretariats'=>$secretariats,'councils'=>$councils]);
    }

    public function ethnicityChart(Request $request)
    {

        $query = User::query();
        if ($request['village'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query = $query->whereHas('agent', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['divisionalType'] == 1) {
            if ($request['pollingBooth'] != null) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('idpolling_booth', $request['pollingBooth']);
                });
            } else if ($request['electionDivision'] != null) {
                $query = $query->whereHas('agent', function ($q) use ($request) {
                    $q->where('idelection_division', $request['electionDivision']);
                });
            }
        } else if ($request['divisionalType'] == 2) {
            if ($request['divisionalSecretariat'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('iddivisional_secretariat', $request['divisionalSecretariat'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('agent', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        } else if ($request['divisionalType'] == 3) {
            if ($request['council'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('idcouncil', $request['council'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query = $query->whereHas('agent', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        }
        $agents = $query->with(['agent.ethnicity'])->where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->where('status', 1)->get();

        $agentsGroup = $agents->groupBy(['agent.idethnicity']);
        $agentCount = count($agents);

        $query1 = User::query();
        if ($request['village'] != null) {
            $query1 = $query1->whereHas('member', function ($q) use ($request) {
                $q->where('idvillage', $request['vilage']);
            });
        } else if ($request['gramasewaDivision'] != null) {
            $query1 = $query1->whereHas('member', function ($q) use ($request) {
                $q->where('idgramasewa_division', $request['gramasewaDivision']);
            });
        } else if ($request['divisionalType'] == 1) {
            if ($request['pollingBooth'] != null) {
                $query1 = $query1->whereHas('member', function ($q) use ($request) {
                    $q->where('idpolling_booth', $request['pollingBooth']);
                });
            } else if ($request['electionDivision'] != null) {
                $query1 = $query1->whereHas('member', function ($q) use ($request) {
                    $q->where('idelection_division', $request['electionDivision']);
                });
            }
        } else if ($request['divisionalType'] == 2) {
            if ($request['divisionalSecretariat'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('iddivisional_secretariat', $request['divisionalSecretariat'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query1 = $query1->whereHas('member', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        } else if ($request['divisionalType'] == 3) {
            if ($request['council'] != null) {
                $gramasewaDivisions = GramasewaDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('idcouncil', $request['council'])->where('status', 1)->select('idgramasewa_division')->get()->toArray();
                $query1 = $query1->whereHas('member', function ($q) use ($gramasewaDivisions) {
                    $q->whereIn('idgramasewa_division', $gramasewaDivisions);
                });
            }
        }

        $members = $query1->with(['member.ethnicity'])->where('iduser_role', 7)->whereHas('member', function ($q) {
            $q->whereHas('memberAgents', function ($q) {
                $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
            });
        })->get();

        $membersGroups = $members->groupBy(['member.idethnicity']);

        $membersCount = count($members);

        return response()->json(['success' => ['agents' => $agentsGroup, 'members' => $membersGroups, 'agent_count' => $agentCount, 'member_count' => $membersCount]]);

    }

    public function voters(Request $request)
    {
        $electionDivisions = ElectionDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        return view('generic_reports.voters')->with(['electionDivisions' => $electionDivisions, 'title' => 'Report : Voters']);

    }

    public function votersChart(Request $request)
    {
        $village = $request['village'];
        $gramasewaDivision = $request['gramasewaDivision'];
        $pollingBooth = $request['pollingBooth'];
        $electionDivision = $request['electionDivision'];

        if ($village != null) {
            $voters = VotersCount::with(['village', 'village.gramasewaDivision', 'village.gramasewaDivision.pollingBooth', 'village.gramasewaDivision.pollingBooth.electionDivision'])->where('idoffice', Auth::user()->idoffice)->where('idvillage', $village)->get();

        } else if ($gramasewaDivision != null) {

            $voters = VotersCount::with(['village', 'village.gramasewaDivision', 'village.gramasewaDivision.pollingBooth', 'village.gramasewaDivision.pollingBooth.electionDivision'])
                ->whereHas('village', function ($q) use ($gramasewaDivision) {
                    $q->where('idgramasewa_division', $gramasewaDivision);
                })
                ->where('idoffice', Auth::user()->idoffice)->get();

        } else if ($pollingBooth != null) {

            $voters = VotersCount::with(['village', 'village.gramasewaDivision', 'village.gramasewaDivision.pollingBooth', 'village.gramasewaDivision.pollingBooth.electionDivision'])
                ->whereHas('village', function ($q) use ($pollingBooth) {
                    $q->where('idpolling_booth', $pollingBooth);
                })
                ->where('idoffice', Auth::user()->idoffice)->get();
        } else if ($electionDivision != null) {

            $voters = VotersCount::with(['village', 'village.gramasewaDivision', 'village.gramasewaDivision.pollingBooth', 'village.gramasewaDivision.pollingBooth.electionDivision'])
                ->whereHas('village', function ($q) use ($electionDivision) {
                    $q->where('idelection_division', $electionDivision);
                })
                ->where('idoffice', Auth::user()->idoffice)->get();
        } else {
            $voters = VotersCount::with(['village', 'village.gramasewaDivision', 'village.gramasewaDivision.pollingBooth', 'village.gramasewaDivision.pollingBooth.electionDivision'])
                ->whereHas('village', function ($q) {
                    $q->where('iddistrict', Auth::user()->office->iddistrict);
                })
                ->where('idoffice', Auth::user()->idoffice)->get();
        }

        return response()->json(['success' => $voters]);
    }

//    public function postResponses(Request $request)
//    {
//        $electionDivisions = ElectionDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
//        $careers = Career::where('status', 1)->get();
//        $religions = Religion::where('status', 1)->get();
//        $incomes = NatureOfIncome::where('status', 1)->get();
//        $educations = EducationalQualification::where('status', 1)->get();
//        $ethnicities = Ethnicity::where('status', 1)->get();
//        $posts = Post::where('idoffice', Auth::user()->idoffice)->where('status', 1)->paginate(15);
//
//        return view('generic_reports.post_response')->with(['ethnicities' => $ethnicities, 'educations' => $educations, 'incomes' => $incomes, 'religions' => $religions, 'careers' => $careers, 'posts' => $posts, 'title' => 'Report : Post Responses', 'electionDivisions' => $electionDivisions]);
//
//    }

    public function communityResponseSummery(Request $request)
    {

        $electionDivisions = ElectionDivision::where('iddistrict', Auth::user()->office->iddistrict)->where('status', 1)->get();
        $careers = Career::where('status', 1)->get();
        $religions = Religion::where('status', 1)->get();
        $incomes = NatureOfIncome::where('status', 1)->get();
        $educations = EducationalQualification::where('status', 1)->get();
        $ethnicities = Ethnicity::where('status', 1)->get();
        $start = date('Y-m-d', strtotime($request['start']));
        $end = date('Y-m-d', strtotime($request['end'] . ' +1 day'));
        $village = $request['village'];
        $gramasewa = $request['gramasewaDivision'];
        $polling = $request['pollingBooth'];
        $election = $request['electionDivision'];
        $category = $request['category'];
        $column = $request['column'];
        if ($category == 1) {
            $tableColumn = 'idreligion';
        } else if ($category == 2) {
            $tableColumn = 'idethnicity';

        } else if ($category == 3) {
            $tableColumn = 'idcareer';

        } else if ($category == 4) {
            $tableColumn = 'ideducational_qualification';

        } else if ($category == 5) {
            $tableColumn = 'idnature_of_income';

        } else {
            $tableColumn = null;
        }

        $query = Post::query();

        $posts = $query->where('idoffice', Auth::user()->idoffice)->where('status', 1)->get()->sortByDesc(function ($q) use ($tableColumn, $category, $column, $village, $gramasewa, $polling, $election, $start, $end) {
            if ($tableColumn != null) {
                if ($start != null && $end != null) {
                    return $q->responses()->whereHas('user', function ($q) use ($tableColumn, $column, $village, $gramasewa, $polling, $election) {
                        $q->whereHas('agent', function ($q) use ($tableColumn, $column, $village, $gramasewa, $polling, $election) {
                            $q->where($tableColumn, $column);
                            if ($village != null) {
                                $q->where('idvillage', $village);
                            }
                            if ($gramasewa != null) {
                                $q->where('idgramasewa_division', $gramasewa);
                            }
                            if ($polling != null) {
                                $q->where('idpolling_booth', $polling);
                            }
                            if ($election != null) {
                                $q->where('idelection_division', $election);
                            }
                        })->orWhereHas('member', function ($q) use ($tableColumn, $column, $village, $gramasewa, $polling, $election) {
                            $q->where($tableColumn, $column);
                            if ($village != null) {
                                $q->where('idvillage', $village);
                            }
                            if ($gramasewa != null) {
                                $q->where('idgramasewa_division', $gramasewa);
                            }
                            if ($polling != null) {
                                $q->where('idpolling_booth', $polling);
                            }
                            if ($election != null) {
                                $q->where('idelection_division', $election);
                            }
                        });
                    })->whereBetween('created_at', [$start, $end])->count();
                } else {

                    return $q->responses()->whereHas('user', function ($q) use ($column, $village, $gramasewa, $polling, $election) {
                        $q->whereHas('agent', function ($q) use ($column, $village, $gramasewa, $polling, $election) {
                            $q->where('idreligion', $column);
                            if ($village != null) {
                                $q->where('idvillage', $village);
                            }
                            if ($gramasewa != null) {
                                $q->where('idgramasewa_division', $gramasewa);
                            }
                            if ($polling != null) {
                                $q->where('idpolling_booth', $polling);
                            }
                            if ($election != null) {
                                $q->where('idelection_division', $election);
                            }
                        })->orWhereHas('member', function ($q) use ($column, $village, $gramasewa, $polling, $election) {
                            $q->where('idreligion', $column);
                            if ($village != null) {
                                $q->where('idvillage', $village);
                            }
                            if ($gramasewa != null) {
                                $q->where('idgramasewa_division', $gramasewa);
                            }
                            if ($polling != null) {
                                $q->where('idpolling_booth', $polling);
                            }
                            if ($election != null) {
                                $q->where('idelection_division', $election);
                            }
                        });
                    })->count();
                }
            } else {
                if ($start != null && $end != null) {
                    return $q->responses()->whereBetween('created_at', [$start, $end])->count();
                } else {

                    return $q->responses()->count();
                }
            }
        });

//        return view('generic_reports.community_response')->with(['ethnicities' => $ethnicities, 'educations' => $educations, 'incomes' => $incomes, 'religions' => $religions, 'careers' => $careers, 'posts' => $posts, 'title' => 'Report : Community Responses', 'electionDivisions' => $electionDivisions]);
        return view('generic_reports.community_response')->with(['ethnicities' => $ethnicities, 'educations' => $educations, 'incomes' => $incomes, 'religions' => $religions, 'careers' => $careers, 'posts' => $posts, 'title' => 'Report : Post Responses Summery', 'electionDivisions' => $electionDivisions]);

    }

    public function getCommunityCategoryValues(Request $request)
    {
        $category = $request['id'];
        if ($category == null) {
            return response()->json(['errors' => ['error' => 'Process Invalid.']]);
        }
        if ($category == 1) {
            $query = Religion::query();
            $id = 'idreligion';
        } else if ($category == 2) {
            $query = Ethnicity::query();
            $id = 'idethnicity';
        } else if ($category == 3) {
            $query = Career::query();
            $id = 'idcareer';
        } else if ($category == 4) {
            $query = EducationalQualification::query();
            $id = 'ideducational_qualification';

        } else if ($category == 5) {
            $query = NatureOfIncome::query();
            $id = 'idnature_of_income';

        }

        $collection = $query->get();
        if ($collection != null) {
            foreach ($collection as $item) {
                $item['id'] = $item->$id;
            }
        }

        return response()->json(['success' => $collection]);

    }

}
