<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'user_id',
        'author',
        'event',
        'previous_state',
        'new_state',
        'table',
        
    ];

     /**
     * Get the user related to the audit.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

     /**
     * Scope a query to search by a given data type and search term.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $dataType
     * @param string|null $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByDataType($query, $dataType, $search)
    {
        if ($dataType && $search) {
            return $query->where($dataType, 'LIKE', "%{$search}%");
        }

        return $query;
    }

     /**
     * Filter by creation dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $creationDateFrom
     * @param string|null $creationDateTo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByCreationDate($query, $creationDateFrom, $creationDateTo)
    {
        if ($creationDateFrom && $creationDateTo) {
            return $query->whereBetween('created_at', [$creationDateFrom, $creationDateTo]);
        } elseif ($creationDateFrom) {
            return $query->where('created_at', '>=', $creationDateFrom);
        } elseif ($creationDateTo) {
            return $query->where('created_at', '<=', $creationDateTo);
        }

        return $query;
    }

   
    /**
     * Filter by update dates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $updateStartDate
     * @param string|null $updateDateTo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByUpdateDate($query, $updateStartDate, $updateDateTo)
    {
        if ($updateStartDate && $updateDateTo) {
            return $query->whereBetween('updated_at', [$updateStartDate, $updateDateTo]);
        } elseif ($updateStartDate) {
            return $query->where('updated_at', '>=', $updateStartDate);
        } elseif ($updateDateTo) {
            return $query->where('updated_at', '<=', $updateDateTo);
        }

        return $query;
    }
    /**
     * Static method to validate search data
     *
     * @param array $data.
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validateSearchData($data)
    {
        return validator($data, [
            'creationDateFrom' => 'nullable|date',
            'creationDateTo' => 'nullable|date|after_or_equal:creationDateFrom',
            'updateStartDate' => 'nullable|date',
            'updateDateTo' => 'nullable|date|after_or_equal:updateStartDate',
        ])->validate();
    }
}
