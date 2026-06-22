<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversionResult extends Model
{
    protected $fillable = [
        'conversion_id',
        'source_subject_id',
        'target_subject_id',
        'origin_grade',
        'grade',
    ];

    /**
     * Get the conversion request this result belongs to.
     */
    public function conversion(): BelongsTo
    {
        return $this->belongsTo(Conversion::class);
    }

    /**
     * Get the source (external) subject.
     */
    public function source_subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'source_subject_id');
    }

    /**
     * Get the target (internal) subject.
     */
    public function target_subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'target_subject_id');
    }
}
