<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $success = Storage::makeDirectory($request->directory . DIRECTORY_SEPARATOR . $request->folder);
        return redirect()->back();
    }


}
