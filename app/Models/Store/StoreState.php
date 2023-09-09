<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreState extends Model
{
    use HasFactory;
    protected $fillable = ["name"];
}
