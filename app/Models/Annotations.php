<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annotations extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Person_of_annotation',
        'Date_of_annotation',
        'Description',
    ];

    public static function validationRules()
    {
        return validator([
            'Person_of_annotation' => ['string', 'max:250', 'nullable'],
            'Date_of_annotation' => ['date', 'nullable'],
            'Description' => ['string', 'max:250', 'nullable'],
        ])->validate();
    }
}
