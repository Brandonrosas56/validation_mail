<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    use HasFactory;

    protected $table = 'metadata';

    protected $fillable = [
        'metadata_identifier',
        'meta_metadata_catalogue',
        'meta_metadata_entry',
        'role_of_metadata_role',
        'role_of_metadata_contributor',
        'role_of_metadata_date',
        'role_of_metadata_email',
        'role_of_metadata_institution',
        'role_of_metadata_country',
        'meta_metadata_entity',
        'meta_metadata_date',
        'meta_metadata_metadata_schema',
        'metadata_language',
    ];


    public static function validationRules()
    {
        return validator( [
            'metadata_identifier' => ['string', 'max:250', 'required'],
            'meta_metadata_catalogue' => ['string', 'max:250', 'nullable'],
            'meta_metadata_entry' => ['string', 'max:250', 'required'],
            'role_of_metadata_role' => ['string', 'max:30', 'nullable'],
            'role_of_metadata_contributor' => ['string', 'max:30', 'nullable'],
            'role_of_metadata_date' => ['string', 'max:30', 'nullable'],
            'role_of_metadata_email' => ['string', 'max:30', 'nullable'],
            'role_of_metadata_institution' => ['string', 'max:30', 'nullable'],
            'role_of_metadata_country' => ['string', 'max:30', 'nullable'],
            'meta_metadata_entity' => ['string', 'max:30', 'nullable'],
            'meta_metadata_date' => ['date', 'nullable'],
            'meta_metadata_metadata_schema' => ['string', 'max:30', 'nullable'],
            'metadata_language' => ['string', 'nullable'],
        ])->validate();
    }
}
