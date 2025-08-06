<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    function jobType()
    {
        return $this->belongsTo(JobType::class);
    }

    function category()
    {
        return $this->belongsTo(Category::class);
    }

    function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
