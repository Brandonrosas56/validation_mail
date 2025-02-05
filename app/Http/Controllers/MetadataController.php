<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\General;
use App\Models\Lifecycle;
use App\Models\Metadata;
use App\Models\Technical_characteristics;
use App\Models\Educational;
use App\Models\Rights;
use App\Models\Relations;
use App\Models\Annotations;
use App\Models\Classification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class MetadataController extends Controller
{
 
    public function show(Request $request)
    {
        $archivoZipId = $request->input('archivoZipId');
       /*  dd( $archivoZipId); */
        return view('metadata.metadata');
    }

    public function store(Request $request, $type)
    {
    $validationRules = [];
    $model = null;
    $successMessage = '';

    switch ($type) {
        case 'general':
            $validationRules = General::validationRules();
            $model = new General;
            $successMessage = 'General ha sido creada exitosamente.';
            break;

        case 'lifecycle':
            $validationRules = Lifecycle::validationRules();
            $model = new Lifecycle;
            $successMessage = 'Ciclo de vida ha sido creada exitosamente.';
            break;

        case 'metadata':
            $validationRules = Metadata::validationRules();
            $model = new Metadata;
            $successMessage = 'Metadata ha sido creada exitosamente.';
            break;

        case 'technical_characteristics':
            $validationRules = Technical_characteristics::validationRules();
            $model = new Technical_characteristics;
            $successMessage = 'Metadata ha sido creada exitosamente.';
            break;

        case 'educational':
            $validationRules = Educational::validationRules();
            $model = new Educational;
            $successMessage = 'La anotación ha sido creada exitosamente.';
            break;

        case 'rights':
            $validationRules = Rights::validationRules();
            $model = new Rights;
            $successMessage = 'La anotación ha sido creada exitosamente.';
            break;

        case 'annotations':
            $validationRules = Annotations::validationRules();
            $model = new Annotations;
            $successMessage = 'La anotación ha sido creada exitosamente.';
            break;

        case 'relations':
            $validationRules = Relations::validationRules();
            $model = new Relations;
            $successMessage = 'Metadata ha sido creada exitosamente.';
            break;

        case 'classification':
            $validationRules = Classification::validationRules();
            $model = new Classification;
            $successMessage = 'Metadata ha sido creada exitosamente.';
            break;

        default:
            return redirect()->route('metadata')->with('error', 'Tipo no válido.');
        }

        $validate = Validator::make($request->all(), $validationRules);

        if ($validate->fails()) {
            return redirect()->route('metadata')->withErrors($validate)->withInput();
        }

        $model->create($request->all());

        if ($type == 'general' || $type == 'lifecycle' || $type == 'metadata' || $type == 'technical_characteristics' || $type == 'educational'  || $type == 'rights' || $type == 'relations' || $type == 'annotations' || $type == 'classification') {
            $directory = $request->input('directory', '/discorepo001');
            $files = Storage::files($directory);
            $directories = Storage::directories($directory);
            $basePath = str_replace('//', '/', $directory);
            $basePath = ($directory !== '/') ? dirname($directory) : $directory;

            return view('metadata.metadata', compact('files', 'directories', 'basePath', 'directory'))->with('success', $successMessage);
        }

        return redirect()->route('metadata')->with('success', $successMessage);
    }
    
}