<?php

namespace App\Http\Controllers;

use App\Models\VersionControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;


class VersionControlController extends Controller
{
    public function index(Request $request){
        $versions = VersionControl::all();
        return view('repo.version-control', compact('versions'));

    }

    public function store(Request $request){
        if(!$request->file('upload_file'))
            return redirect()->back();

        echo $request->directory;
        $file = $request->file('upload_file');
        $fileName = $file->getClientOriginalName();
        if(Storage::exists($request->directory . DIRECTORY_SEPARATOR . $fileName)){
            $newName = time() . '_' . $fileName;
            //

            if(!Storage::exists($request->directory))
                Storage::makeDirectory('allversions');
            $status = Storage::copy($request->directory . DIRECTORY_SEPARATOR . $fileName, 'allversions' . DIRECTORY_SEPARATOR . $newName);
            if($status)
                ($this->saveVersion($file, $request, $newName ));
        }
        $file->storeAs($request->directory, $fileName);
        return redirect()->back();
    }

    protected function saveVersion($file, $request, $newName ){
        //     $table->enum('action', ['Actualizado', 'Elimnado', 'Restaurado']);
        $nameFile = $request->directory . DIRECTORY_SEPARATOR . $file->getClientOriginalName();
        $version = VersionControl::where('name_file', $nameFile)
        ->get()->count();

        VersionControl::create(
            [
                'user_id' => Auth::user()->id,
                'name_file' => $nameFile,
                'new_name' => $newName,
                'type' => $file->getClientOriginalName(),
                'route'=> $request->directory,
                'old_version' => $version,
                'new_version' => $version + 1,
                'action' => 'Actualizado'
            ]
        );
    }

    public function restore(Request $request){
        $validate = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if($validate->fails())
            return redirect()->back()->withErrors($validate->errors());

        //First
        $file = VersionControl::where('id', $request->id)->first();

        $newName = time() . '_' . $file->type;
        $status = Storage::copy($file->name_file, 'allversions' . DIRECTORY_SEPARATOR . $newName);
        if($status){
            $version = VersionControl::where('name_file', $file->name_file)
            ->get()->count();
            VersionControl::create(
                [
                    'user_id' => Auth::user()->id,
                    'name_file' => $file->name_file,
                    'new_name' => $newName,
                    'type' => $file->type,
                    'route'=> $file->route,
                    'old_version' => $version,
                    'new_version' => $version + 1,
                    'action' => 'Restaurado'
                ]
            );
            Storage::delete($file->name_file);
            Storage::copy('allversions' . DIRECTORY_SEPARATOR . $file->new_name, $file->name_file);
        }
        return redirect()->back();
    }


}
