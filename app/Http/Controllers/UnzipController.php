<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ZipFile;

class UnzipController extends Controller
{
    /**
     * Maneja la solicitud para procesar un archivo ZIP.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP entrante.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request){
        $data = $request->all();
        ZipFile::dispatch($data);
        
     
        /* return redirect()->back(); */
        return redirect()->route('zipReport');
    }
}


