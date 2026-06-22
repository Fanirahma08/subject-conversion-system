<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'sks',
        'semester',
        'university_id',
        'prodi',
        'is_active',
    ];

    /**
     * Get the university this subject belongs to.
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get the mappings where this subject is the source (External).
     */
    public function source_mappings(): HasMany
    {
        return $this->hasMany(SubjectMapping::class, 'source_subject_id');
    }

    /**
     * Get the mappings where this subject is the target (Internal).
     */
    public function target_mappings(): HasMany
    {
        return $this->hasMany(SubjectMapping::class, 'target_subject_id');
    }

    /**
     * Get the mapped internal subjects.
     */
    public function mapped_subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_mappings', 'source_subject_id', 'target_subject_id');
    }

    /**
     * Scope a query to only include active subjects.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
