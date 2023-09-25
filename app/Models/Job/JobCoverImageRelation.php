<?php

namespace App\Models\Job;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCoverImageRelation extends Model
{
    use HasFactory;
    protected $fillable = ["media_library_id", "job_id", "primary"];
}
