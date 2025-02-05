<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class listRepo extends Controller
{
    public function index(Request $request){
        $routeBase = '';
        if(config('APP_ROUTE')== ''){
            $routeBase = 'Raiz';
            $routeFile = config('APP_ROUTE');
        }else{
            $routeBase .= $request->routeB;
            $routeFile = config('APP_ROUTE');
        }
        $directory = $request->input('directory', '/discorepo001');
        $files = Storage::files($directory); //
        $directories = Storage::directories($directory);
        $basePath = str_replace('//', '/', $directory);
        $basePath = ($directory !== '/') ? dirname($directory) : $directory;
        return view('repo.list', compact('routeBase', 'files', 'directories', 'basePath', 'directory'));

    }

    public function showMetadata(Request $request)
    {
        $routeBase = '';
        if (config('APP_ROUTE') == '') {
            $routeBase = 'Raiz';
            $routeFile = config('APP_ROUTE');
        } else {
            $routeBase .= $request->routeB;
            $routeFile = config('APP_ROUTE');
        }
        
        $directory = $request->input('directory', '/discorepo001');
        $files = Storage::files($directory);
        $directories = Storage::directories($directory);
        $basePath = str_replace('//', '/', $directory);
        $basePath = ($directory !== '/') ? dirname($directory) : $directory;

        return view('metadata.metadata', compact('routeBase', 'files', 'directories', 'basePath', 'directory')); 
    }
}
