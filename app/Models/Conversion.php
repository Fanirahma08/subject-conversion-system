<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversion extends Model
{
    protected $fillable = [
        'user_id',
        'transcript_path',
        'registration_letter_path',
        'ktp_path',
        'status',
        'notes',
        'reviewed_by',
        'decree_number',
        'decree_date',
        'academic_year',
        'rector_name',
        'rector_nidn',
        'graduation_total_sks',
    ];

    /**
     * Get the student who owns the conversion request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the staff (PMB/Kaprodi) who reviewed the request.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the individual mapping results for this conversion request.
     */
    public function results()
    {
        return $this->hasMany(ConversionResult::class);
    }
}
