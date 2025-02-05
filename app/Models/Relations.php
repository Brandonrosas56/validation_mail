<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relations extends Model
{
    use HasFactory;

    protected $table = 'relations';

    protected $fillable = [
        'type_of_relationship',
        'relationship_identifier',
        'description_of_relations',
        'relationship_catalogue',
        'relationship_entry',
    ];


    public static function validationRules()
    {
        return validator([
            'type_of_relationship' => ['string', 'max:250'],
            'relationship_identifier' => ['string', 'max:250'],
            'description_of_relations' => ['string', 'max:250'],
            'relationship_catalogue' => ['string', 'max:250'],
            'relationship_entry' => ['string', 'max:250'],
        ])->validate();
    }
}


