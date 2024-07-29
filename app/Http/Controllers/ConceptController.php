<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Concept;

class ConceptController extends Controller
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

        $concepts = Concept::where('visible', 1)
        ->get();

        return view('concepts.index', compact('concepts'));
    }
    
} // class
