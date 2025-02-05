<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\ArchivoZip;

class zipReportController extends Controller
{
    /**
     * Muestra una lista de todos los archivos ZIP almacenados.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $archivosZip = ArchivoZip::all();   
        return view('repo.zipReport', compact('archivosZip'));
    }
  
}
