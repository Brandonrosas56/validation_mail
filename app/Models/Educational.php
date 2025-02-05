<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Educational extends Model
{
    use HasFactory;

    protected $table = 'educational';

    protected $fillable = [
        'type_of_educational_interactivity',
        'type_resources',
        'level_of_interactivity',
        'educational_semantic_density',
        'educational_user_role',
        'educational_context',
        'context',
        'educational_age_range',
        'educational_difficulty',
        'learning_time',
        'educational_description',
        'educational_language'
    ];

    public static function validationRules()
    {
        return validator([
            'type_of_educational_interactivity' => ['required', 'string'],
            'type_resources' => ['required', 'string', 'max:250'],
            'level_of_interactivity' => ['required', 'string'],
            'educational_semantic_density' => ['nullable', 'string'],
            'educational_user_role' => ['nullable', 'string', 'max:250'],
            'educational_context' => ['nullable', 'string', 'max:250'],
            'context' => ['nullable', 'string', 'max:250'],
            'educational_age_range' => ['nullable', 'string', 'max:250'],
            'educational_difficulty' => ['nullable', 'string'],
            'learning_time' => ['nullable', 'string', 'max:250'],
            'educational_description' => ['required', 'string', 'max:250'],
            'educational_language' => ['required', 'string', 'max:250'],
        ])->validate();
    }
}
