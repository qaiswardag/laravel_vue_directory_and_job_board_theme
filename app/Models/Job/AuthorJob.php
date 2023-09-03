<?php

namespace App\Models\Job;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorJob extends Model
{
    use HasFactory;
    // Define the table name for this model
    protected $table = "author_job";

    // Define the fillable attributes
    protected $fillable = ["user_id", "job_id"];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
