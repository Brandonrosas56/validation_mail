<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivoZip extends Model
{
    use HasFactory;

    protected $table = 'archivozip';

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'path',
        'state',
        'idArchivoZip',
    ];

     /**
     * Método estático público para crear un registro de ArchivoZip.
     *
     * @param string $name
     * @param string $path
     * @return \App\Models\ArchivoZip
     */
    public static function createArchivoZip($name, $path, $state)
    {
        return self::create([
            'name' => $name,
            'path' => $path,
            'state'=>  $state,
        ]);
    }
    // Método para verificar si el archivo ZIP ya existe
    public static function exists($name, $path)
    {
        return self::where('name', $name)->where('path', $path)->exists();
    }
}
