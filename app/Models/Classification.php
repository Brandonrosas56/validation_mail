<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    use HasFactory;


    protected $table = 'classification';

    protected $fillable = [
        'purpose_of_classification',
        'origin_of_the_classification',
        'classification_taxon',
        'classification_id',
        'classification_entry',
        'description_of_the_classification',
        'classification_keywords',
        'name_of_the_programme',
        'programme_code',
        'knowledge_network',
        'occupational_area',
        'training_center',
    ];

    public static function validationRules()
    {
        return validator([
            'purpose_of_classification' => ['string', 'max:250', 'nullable'],
            'origin_of_the_classification' => ['string', 'max:250', 'required'],
            'classification_taxon' => ['string', 'max:250', 'nullable'],
            'classification_id' => ['string', 'max:250', 'nullable'],
            'classification_entry' => ['string', 'max:250', 'nullable'],
            'description_of_the_classification' => ['string', 'max:250', 'nullable'],
            'classification_keywords' => ['string', 'max:250', 'nullable'],
            'name_of_the_programme' => ['string', 'max:250', 'required'],
            'programme_code' => ['string', 'max:250', 'required'],
            'knowledge_network' => ['string', 'max:250', 'required'],
            'occupational_area' => ['string', 'max:250', 'required'],
            'training_center' => ['string', 'max:250', 'required'],
        ])->validate();
    }
}
