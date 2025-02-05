<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rights extends Model
{
    use HasFactory;

    protected $fillable = [
        'contributors_contributors',
        'contributors_role',
        'contributors_date',
        'contributors_type',
        'contributors_contact',
        'contributors_identifier',
        'contributors_country_of_origin',
        'contributors_institution',
        'cost_of_fees',
        'copyright_and_other_restrictions',
        'description_of_rights',
        'right_of_appeal',
        'availability',
    ];

    public static function validationRules()
    {
        return validator([
            'contributors_contributors' => ['string', 'max:255'],
            'contributors_role' => ['string', 'max:255'],
            'contributors_date' => ['string', 'max:255'],
            'contributors_type' => ['string', 'max:255'],
            'contributors_contact' => ['string', 'max:255'],
            'contributors_identifier' => ['string', 'max:255'],
            'contributors_country_of_origin' => ['string', 'max:255'],
            'contributors_institution' => ['string', 'max:255'],
            'cost_of_fees' => ['string', 'max:255'],
            'copyright_and_other_restrictions' => ['string', 'max:255'],
            'description_of_rights' => ['string', 'max:255'],
            'right_of_appeal' => ['string', 'max:255'],
            'availability' => ['string', 'max:255'],
        ])->validate();
    }

    
}
