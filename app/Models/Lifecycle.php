<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lifecycle extends Model
{
    use HasFactory;

    protected $table = 'lifecycle'; // Especificar el nombre de la tabla

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'life_cycle_version',
        'life_cycle_status',
        'role_life_cycle',
        'life_cycle_entity',
        'date',
    ];

    public static function validationRules()
    {
        return validator([
            'life_cycle_version' => ['string', 'max:12', 'required'],
            'life_cycle_status' => ['string', 'max:40', 'required'],
            'role_life_cycle' => ['string', 'required'], 
            'life_cycle_entity' => ['string', 'max:50', 'required'],
            'date' => ['date', 'required'],
        ])->validate();
    }
}
