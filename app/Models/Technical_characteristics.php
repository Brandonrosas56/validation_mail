<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technical_characteristics extends Model
{
    use HasFactory;

    protected $table = 'technical_characteristics';

    protected $fillable = [
        'technical_format',
        'technical_size',
        'technical_location',
        'technical_location_web',
        'technical_location_source',
        'technical_type',
        'technical_name',
        'minimum_technical_version',
        'maximum_technical_version',
        'technical_requirements_for_other_platforms_type',
        'technical_requirements_for_other_platforms_instruction',
        'technical_requirements_for_other_platforms_source',
        'technical_requirements_for_other_platforms_language',
        'technical_duration',
        'use',
    ];

    public static function validationRules()
    {
        return validator( [
            'technical_format' => ['string', 'max:250', 'required'],
            'technical_size' => ['string', 'max:50', 'required'],
            'technical_location' => ['string', 'max:250', 'required'],
            'technical_location_web' => ['string', 'max:250', 'required'],
            'technical_location_source' => ['string', 'max:250', 'required'],
            'technical_type' => ['string', 'max:250', 'nullable'],
            'technical_name' => ['string', 'max:250', 'nullable'],
            'minimum_technical_version' => ['string', 'max:250', 'nullable'],
            'maximum_technical_version' => ['string', 'max:250', 'nullable'],
            'technical_requirements_for_other_platforms_type' => ['string', 'max:50', 'nullable'],
            'technical_requirements_for_other_platforms_instruction' => ['string', 'max:50', 'nullable'],
            'technical_requirements_for_other_platforms_source' => ['string', 'max:100', 'nullable'],
            'technical_requirements_for_other_platforms_language' => ['string', 'max:50', 'nullable'],
            'technical_duration' => ['string', 'nullable'],
            'use' => ['string', 'nullable'],
        ])->validate();
    }
    
}
