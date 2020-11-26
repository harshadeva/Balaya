<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HierarchyController extends Controller
{
    public function index(){
        return view('hierarchy.build')->with(['title'=>'Build Hierarchy']);
    }
}
