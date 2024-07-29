<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Calendar;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index()
    {
    
        $calendars = Calendar::where('almcnt', Auth::user()->almcnt)
        ->where('year', Auth::user()->currentYear)
        ->get();

        return view('calendars.index', compact('calendars'));
    }

} // class
