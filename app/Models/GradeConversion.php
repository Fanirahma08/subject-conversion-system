<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeConversion extends Model
{
    protected $fillable = [
        'origin_grade',
        'internal_grade',
    ];
}
