<?php

namespace App\Models\Job;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCategoryRelation extends Model
{
    use HasFactory;

    protected $fillable = ["job_id", "category_id"];
}
