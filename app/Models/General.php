<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    use HasFactory;

    protected $table = 'general';

    protected $fillable = [
        'general_identifier',
        'general_heading',
        'subtitle',
        'general_catalog',
        'general_admission',
        'language',
        'description',
        'keywords',
        'coverage_name_of_the_period',
        'coverage_classification_scheme_1',
        'coverage_time',
        'coverage_classification_scheme_2',
        'coverage_classification_scheme_3',
        'coverage_continent',
        'coverage_country',
        'coverage_region',
        'coverage_state',
        'coverage_city',
        'coverage_zone',
        'coverage_address',
        'general_structure',
        'grouping_level',
    ];

    public static function validationRules()
    {
        return validator([
            'general_identifier' => ['string', 'max:250', 'required'],
            'general_heading' => ['string', 'max:250', 'required'],
            'subtitle' => ['string', 'max:250', 'nullable'],
            'general_catalog' => ['string', 'max:250', 'required'],
            'general_admission' => ['string', 'max:250', 'required'],
            'language' => ['string', 'max:250', 'required'],
            'description' => ['string', 'max:350', 'required'],
            'keywords' => ['string', 'max:250', 'required'],
            'coverage_name_of_the_period' => ['string', 'max:250', 'nullable'],
            'coverage_classification_scheme_1' => ['string', 'max:250', 'nullable'],
            'coverage_time' => ['string', 'max:250', 'nullable'],
            'coverage_classification_scheme_2' => ['string', 'max:250', 'nullable'],
            'coverage_classification_scheme_3' => ['string', 'max:250', 'nullable'],
            'coverage_continent' => ['string', 'max:250', 'nullable'],
            'coverage_country' => ['string', 'max:250', 'nullable'],
            'coverage_region' => ['string', 'max:250', 'nullable'],
            'coverage_state' => ['string', 'max:250', 'nullable'],
            'coverage_city' => ['string', 'max:250', 'nullable'],
            'coverage_zone' => ['string', 'max:250', 'nullable'],
            'coverage_address' => ['string', 'max:250', 'nullable'],
            'general_structure' => ['string', 'max:250', 'required'],
            'grouping_level' => ['string', 'max:250', 'nullable'],
        ])->validate();
    }
}
