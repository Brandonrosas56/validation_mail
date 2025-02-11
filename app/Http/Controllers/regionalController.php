<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class regionalController extends Controller
{
    public function store()
    {
        return view('import.import-regional');
    }

    public function importRegional(Request $request)
    {
        if ($request->hasFile('upload_file') && $request->file('upload_file')->isValid()) {
            $file = $request->file('upload_file');
            $extension = $file->extension();
            if ($extension !== 'csv') {
                return redirect()->back()
                    ->withErrors(['error' => 'Este archivo tiene que ser de tipo csv'])
                    ->withInput();
            } else {
                $filePath = $file->getRealPath();
                $csvData = [];

                if (($handle = fopen($filePath, "r")) !== false) {
                    $csvData = [];
                    $header = fgetcsv($handle, 1000, ",");

                    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                        if (count($row) === count($header)) { 
                            $csvData[] = array_combine($header, $row); 
                        }
                    }
                    fclose($handle);
                }
                $this->insertRegional($csvData);
            }
        } else {
            return redirect()->back()
                ->withErrors(['error' => 'No ha seleccionado ningún archivo'])
                ->withInput();
        }
    }


    public function insertRegional($csvData)
    {
        try {
            foreach ($csvData as $rowData) {
                $regionals[] = [
                    'rgn_id' => (int) $rowData['rgn_id'],
                    'rgn_nombre' => $rowData['rgn_nombre'],
                    'rgn_direccion' => $rowData['rgn_direccion'],
                    'pai_id' => (int) $rowData['pai_id'],
                    'pai_nombre' => $rowData['pai_nombre'],
                    'dpt_id' => (int) $rowData['dpt_id'],
                    'dpt_nombre' => $rowData['dpt_nombre'],
                    'mpo_id' => (int) $rowData['mpo_id'],
                    'mpo_nombre' => $rowData['mpo_nombre'],
                    'zon_id' => $rowData['zon_id'] ? (int) $rowData['zon_id'] : null,
                    'zon_nombre' => $rowData['zon_nombre'] ?: '',
                    'bar_id' => $rowData['bar_id'] ? (int) $rowData['bar_id'] : null,
                    'bar_nombre' => $rowData['bar_nombre'] ?: '',
                    'rgn_fch_registro' => $rowData['rgn_fch_registro'],
                    'rgn_estado' => (int) $rowData['rgn_estado'],
                ];
            }

            DB::table('regional')->upsert(
                $regionals,  
                ['rgn_id'],
                ['rgn_nombre', 'rgn_direccion', 'pai_id', 'pai_nombre', 'dpt_id', 'dpt_nombre', 'mpo_id', 'mpo_nombre', 'zon_id', 'zon_nombre', 'bar_id', 'bar_nombre', 'rgn_fch_registro', 'rgn_estado'] // Columnas a actualizar si el registro ya existe
            );

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Por favor revice que el archivo ese correctamente redactado ' . $e->getMessage()])
                ->withInput()->send();
        }
        return redirect()->route('show-regional')->with('success', '!Datos guardados correctamente¡');
    }
}
