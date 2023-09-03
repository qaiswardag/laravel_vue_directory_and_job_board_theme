<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorStore extends Model
{
    use HasFactory;
    // Define the table name for this model
    protected $table = "author_store";

    // Define the fillable attributes
    protected $fillable = ["user_id", "store_id"];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
