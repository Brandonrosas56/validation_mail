<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Archivo;
use App\Models\ArchivoZip;

class moveFileController extends Controller
{
    //
    public function selectFile(Request $request)
    {
        $currentPath = $request->input('currentPath');
        $directoriesList = $this->listFolders();
        $nameFile = basename($currentPath);
        $typeFile = substr($currentPath, -4);

        Session::put('currentPath', $currentPath);
        Session::put('nameFile', $nameFile);
        Session::put('typeFile', $typeFile);

        return view('repo.listFolder', ['directoriesList' => $directoriesList]);
    }

    public function listFolders()
    {
        $directoryPath = storage_path(DIRECTORY_SEPARATOR . 'app');
        $directories = File::directories($directoryPath);

        $directoriesList = array_map('basename', $directories);

        return $directoriesList;
    }


    public function moveFile(Request $request)
    {
        $currentPath = Session::get('currentPath');
        $nameFile = Session::get('nameFile');
        $typeFile = Session::get('typeFile');
        $newPath = $request->folder . DIRECTORY_SEPARATOR . $nameFile;

        if (!Storage::exists($request->folder)) {
            return redirect()->back()->withErrors(['error' => 'La carpeta de destino no existe'])->withInput();
        }

        try {
            Storage::move($currentPath, $newPath);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al mover el archivo: ' . $e->getMessage()])->withInput();
        }

        if($typeFile === '.zip'){
            $idZip = ArchivoZip::where('name','like','%'.$nameFile)->value('id');
            echo $idZip;
            $archivoZip = ArchivoZip::findOrFail($idZip);
            $archivoZip->path = $newPath;
            $archivoZip->save();
        }
        else{
            $idFile = Archivo::where('route','like','%'.$currentPath)->value('id');
            $archivo = Archivo::findOrFail($idFile);
            $archivo->route =  $newPath;
            $archivo->save();
        }

        return redirect()->route('list');
    }
}
