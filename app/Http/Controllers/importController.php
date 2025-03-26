<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Regional;

class importController extends Controller
{
    /**
     * Muestra el formulario para cargar archivos.
     *
     * Este método retorna la vista con el formulario que permite al usuario cargar archivos CSV.
     *
     * @return \Illuminate\View\View
     */
    public function store()
    {
        return view('import.import-files');
    }

    /**
     * Maneja la carga y procesamiento de los archivos CSV.
     *
     * Este método verifica que el archivo cargado sea válido, y dependiendo del tipo de archivo,
     * lo procesa y lo inserta en la base de datos, ya sea para registrar regionales o administradores.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importFiles(Request $request)
    {
        // Verifica si el archivo ha sido cargado correctamente y si es válido
        if ($request->hasFile('upload_file') && $request->file('upload_file')->isValid()) {
            $file = $request->file('upload_file');
            $extension = $file->getClientOriginalExtension();

            // Si el archivo no es CSV, muestra un mensaje de error
            if ($extension !== 'csv') {
                return redirect()->back()
                    ->withErrors(['error' => 'Este archivo tiene que ser de tipo csv'])
                    ->withInput();
            } else {
                $filePath = $file->getRealPath();
                $csvData = [];

                // Abre el archivo CSV y lo procesa línea por línea
                if (($handle = fopen($filePath, "r")) !== false) {
                    $csvData = [];
                    $header = fgetcsv($handle, 1000, ",");  // Lee la cabecera del archivo CSV

                    // Lee las filas del archivo CSV
                    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                        if (count($row) === count($header)) {
                            $csvData[] = array_combine($header, $row);  // Combina la cabecera con los datos
                        }
                    }
                    fclose($handle);
                }

                // Dependiendo del tipo de archivo, procesa los datos para las regionales o administradores
                if ($request->typeFile === 'Regional') {
                    return $this->insertRegional($csvData);  // Inserta los datos de las regionales
                } elseif ($request->typeFile === 'Administrators') {
                    return $this->insertAdministrators($csvData);  // Inserta los datos de los administradores
                }
            }
        } else {
            // Si no se seleccionó ningún archivo, muestra un mensaje de error
            return redirect()->back()
                ->withErrors(['error' => 'No ha seleccionado ningún archivo'])
                ->withInput();
        }
    }

    /**
     * Registra los datos de las regionales en la base de datos.
     *
     * Este método procesa los datos del archivo CSV y los inserta o actualiza en la tabla 'regional'.
     *
     * @param  array  $csvData
     * @return \Illuminate\Http\RedirectResponse
     */
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

            // Inserta o actualiza los registros en la tabla 'regional'
            DB::table('regional')->upsert(
                $regionals,
                ['rgn_id'],
                ['rgn_nombre', 'rgn_direccion', 'pai_id', 'pai_nombre', 'dpt_id', 'dpt_nombre', 'mpo_id', 'mpo_nombre', 'zon_id', 'zon_nombre', 'bar_id', 'bar_nombre', 'rgn_fch_registro', 'rgn_estado']
            );
        } catch (Exception $e) {
            // Si ocurre un error, muestra un mensaje de error
            return redirect()->back()
                ->withErrors(['error' => 'Por favor revise que el tipo de archivo sea el correcto'])
                ->withInput()->send();
        }

        // Si todo es exitoso, redirige con un mensaje de éxito
        return redirect()->route('show-import')->with('success', '¡Datos guardados correctamente!');
    }

    /**
     * Registra los datos de los administradores en la base de datos.
     *
     * Este método procesa los datos del archivo CSV y los inserta o actualiza en la tabla 'users'.
     * Verifica que las regionales existan antes de registrar a los administradores.
     *
     * @param  array  $csvData
     * @return \Illuminate\Http\RedirectResponse
     */
    public function insertAdministrators($csvData)
    {
        try {
            // Obtiene todas las regionales
            $regionals = Regional::all('rgn_id', 'rgn_nombre');

            // Si no existen regionales, muestra un mensaje de error
            if ($regionals->isEmpty()) {
                return redirect()->back()->withErrors(['error' => 'Por favor importe primero las regionales'])->withInput()->send();
            } else {
                $now = Carbon::now();
                $userId = Auth::id();

                // Procesa cada fila del archivo CSV
                foreach ($csvData as $rowData) {
                    // Verifica si la regional existe
                    $regional = $regionals->firstWhere('rgn_nombre', $rowData['regional']);

                    if (empty(trim($rowData['regional']))) {
                        $rgnId = null;
                    } else {
                        $regional = $regionals->firstWhere('rgn_nombre', trim($rowData['regional']));

                        // Si no se encuentra la regional, muestra un mensaje de error
                        if (!$regional) {
                            return redirect()->back()->withErrors([
                                'error' => 'No se encontró la regional: ' . $rowData['regional']
                            ])->withInput()->send();
                        }

                        $rgnId = $regional->rgn_id;
                    }

                    // Inserta o actualiza el administrador en la base de datos
                    $User = User::updateOrCreate(
                        ['supplier_document' => $rowData['supplier_document'], 'email' => $rowData['email']],
                        [
                            'name' => $rowData['name'],
                            'position' => $rowData['position'],
                            'rgn_id' => $rgnId,
                            'registrar_id' => $userId,
                            'lock' => false,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]
                    );

                    // Asocia el rol 'Admin' al usuario
                    $User->syncRoles(['Admin']);
                }
            }
        } catch (Exception $e) {
            // Si ocurre un error, muestra un mensaje de error
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput()->send();
        }

        // Si todo es exitoso, redirige con un mensaje de éxito
        return redirect()->back()->with('success', '¡Datos guardados correctamente!');
    }
}
