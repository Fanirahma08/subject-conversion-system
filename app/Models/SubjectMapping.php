<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'prodi',
        'source_subject_id',
        'target_subject_id',
    ];

    /**
     * Get the university this mapping belongs to.
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get the source (External) subject.
     */
    public function source_subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'source_subject_id');
    }

    /**
     * Get the target (Internal) subject.
     */
    public function target_subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'target_subject_id');
    }
}
