<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    function job()
    {
        return $this->belongsTo(Job::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }
}
