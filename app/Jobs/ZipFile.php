<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use ZipArchive;
use App\Models\ArchivoZip;
use App\Models\Archivo;




class ZipFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     // Variable protegida para almacenar los datos pasados al constructor
    protected $data;

    /**
     * Constructor de la clase.
     *
     * @param array $data Datos necesarios para el manejo del archivo ZIP.
     */
    public function __construct( $data)
    {
        $this->data = $data;
       
    }

    /**
     * Maneja la tarea de procesamiento del archivo ZIP.
     */
    public function handle()
    {
        Log::info('hola' );
        Log::info(json_encode($this->data) );
        try {
            $file = $this->data['file']; // "discorepo001/pruebalu1.zip"
            $path = Storage::path($file); // "/var/www/html/wilson/reposena/storage/app/discorepo001/pruebalu1.zip"
            $directory = Storage::path($this->data['directory']); // "/var/www/html/wilson/reposena/storage/app/discorepo001"

            $zip = new ZipArchive;

            // Listar archivos presentes en el directorio antes de la extracción
            $existingFiles = File::allFiles($directory);
            $existingFilePaths = [];
            foreach ($existingFiles as $existingFile) {
                $existingFilePaths[] = $existingFile->getPathname();
            }

            if ($zip->open($path) === true) {
                $zip->extractTo($directory); // Extract the contents to the directory
                $zip->close();
                $zipName = basename($path); // "pruebalu1.zip"
                $archivoZip = null;

                if (!ArchivoZip::exists($zipName, $directory)) {
                    $state ='completado';
                    $archivoZip = ArchivoZip::createArchivoZip($zipName, $directory, $state);
                    // Guardar archivos extraídos y devolver el ID del archivo ZIP creado
                    $this->guardarArchivosExtraidos($directory, $existingFilePaths, $archivoZip);
                }
            } else {
                Log::error('Failed to open the zip file: ' . $path);
            }
        } catch (\Exception $e) {
            $state ='Fallido ' . $e->getMessage() ;
            $archivoZip = ArchivoZip::createArchivoZip($zipName, $directory, $state);
           
        }
    }

    /**
     * Guarda los archivos extraídos del directorio en la base de datos.
     *
     * @param string $directory Ruta del directorio donde se extrajeron los archivos.
     * @param array $existingFilePaths Rutas de archivos existentes antes de la extracción.
     * @param ArchivoZip $archivoZip Instancia del archivo ZIP asociado.
     * @return int El ID del archivo ZIP asociado.
     */
    private function guardarArchivosExtraidos($directory, $existingFilePaths, $archivoZip)
    {
        $allFiles = File::allFiles($directory);
        foreach ($allFiles as $extractedFile)  {
            if (!in_array($extractedFile->getPathname(), $existingFilePaths)) {
                Archivo::createArchivo(
                    $extractedFile->getFilename(),
                    $extractedFile->getPathname(),
                    $archivoZip->id
                );
            }
        }
        return $archivoZip->id;
    }
}
