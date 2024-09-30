<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Exports\PercepcionesDeduccionesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function export(Request $request)
    {
        $year = $request->input('year');
        $almcnt = $request->input('almcnt');
        $semana = $request->input('semana');

        return Excel::download(new PercepcionesDeduccionesExport($year, $almcnt, $semana), 'percepciones_deducciones.xlsx');
    }

    public function acumulado()
    {

       // Generar PDF
       return view('reports.report');

    }
    
    
} // class
