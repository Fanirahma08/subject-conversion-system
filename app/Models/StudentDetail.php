<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentDetail extends Model
{
    protected $fillable = [
        'user_id',
        'university_id',
        'prodi_origin',
        'graduation_date',
        'nim',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'address',
        'phone',
        'father_name',
        'mother_name',
    ];

    protected function casts(): array
    {
        return [
            'graduation_date' => 'date',
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Get the university the student is from.
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get the user that owns the student details.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
