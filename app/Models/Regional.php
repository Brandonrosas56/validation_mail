<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regional extends Model
{
    protected $table = 'regional';

    protected $fillable = [
        'rgn_id',
        'rgn_nombre', 
        'rgn_direccion',
        'pai_id',
        'pai_nombre', 
        'dpt_id',
        'dpt_nombre',
        'mpo_id',
        'mpo_nombre',
        'zon_id',
        'zon_nombre', 
        'bar_id',
        'bar_nombre', 
        'rgn_fch_registro',
        'rgn_estado',
    ];
    
}
