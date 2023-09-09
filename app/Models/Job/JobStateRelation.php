<?php

namespace App\Models\Job;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobStateRelation extends Model
{
    use HasFactory;
    protected $fillable = ["job_id", "state_id"];
}
