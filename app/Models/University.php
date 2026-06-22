<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class University extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Get the students from this university.
     */
    public function students(): HasMany
    {
        return $this->hasMany(StudentDetail::class);
    }

    /**
     * Get the subjects associated with this university.
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
