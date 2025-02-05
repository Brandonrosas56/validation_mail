<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'route',
        'idArchivoZip',
    ];

    // Método para crear un nuevo archivo
    public static function createArchivo($name, $route, $idArchivoZip)
    {
        return self::create([
            'name' => $name,
            'route' => $route,
            'idArchivoZip' => $idArchivoZip,
        ]);
    }
}