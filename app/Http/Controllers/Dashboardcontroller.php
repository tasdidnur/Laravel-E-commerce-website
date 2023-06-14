<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Dashboardcontroller extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        // return view('layouts.dashboard');
         return view('dashboard.index');
    }
}
