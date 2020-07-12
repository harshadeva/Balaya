<?php

namespace App\Http\Controllers;

use App\ElectionDivision;
use App\GramasewaDivision;
use App\StaffElectionDivisions;
use App\StaffGramasewaDivision;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        $electionDivisions = ElectionDivision::where('status', 1)->where('iddistrict', Auth::user()->office->iddistrict)->get();
        $users = User::where('idoffice', Auth::user()->idoffice)->where('iduser_role', 8)->where('status', 1)->paginate(10);
        return view('staff.assign_staff')->with(['title' => 'Assign Staff', 'users' => $users, 'electionDivisions' => $electionDivisions]);

    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'electionDivisions' => 'required|array|min:1',
            'staffId' => 'required'

        ], [
            'electionDivisions.required' => 'Please assign  one or more election division!',
            'staffId.required' => 'Invalid staff!',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $electionDivisions = $request['electionDivisions'];
        $user = User::where('idoffice', Auth::user()->idoffice)->where('status', 1)->where('idUser', $request['staffId'])->first();

        if ($user != null) {
            foreach ($electionDivisions as $electionDivision) {

                $isExist = StaffElectionDivisions::where('idoffice', Auth::user()->idoffice)->where('idelection_division', $electionDivision)->where('status', 1)->first();

                if ($isExist != null) {
                    return response()->json(['errors' => ['error' => '`' . ElectionDivision::find(intval($electionDivision))->name_en . '` election division has already assigned to `' . strtoupper($isExist->mediaStaff->fName) . '``.']]);
                }
            }

            foreach ($electionDivisions as $electionDivision) {

                $staffElectionDivision = new StaffElectionDivisions();
                $staffElectionDivision->idmedia_staff = $user->idUser;
                $staffElectionDivision->idelection_division = $electionDivision;
                $staffElectionDivision->idoffice = Auth::user()->office->idoffice;
                $staffElectionDivision->status = 1;
                $staffElectionDivision->save();

            }

            $count = StaffElectionDivisions::where('idmedia_staff', $user->idUser)->where('status', 1)->count();
        } else {
            return response()->json(['errors' => ['error' => 'Invalid staff.']]);
        }
        return response()->json(['success' => 'Staff assigned Successfully!', 'count' => $count]);

    }

    public function viewAssignedDivision(Request $request)
    {
        $officeElection  = StaffElectionDivisions::with(['electionDivision'])->where('idmedia_staff', $request['id'])->where('status', 1)->select(['idmedia_staff', 'idelection_division'])->latest()->get();
        if ($officeElection != null) {
            return response()->json(['success' => $officeElection]);
        } else {
            return response()->json(['errors' => ['error' => 'Invalid staff.']]);
        }
    }

}
