<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreStateRelation extends Model
{
    use HasFactory;
    protected $fillable = ["store_id", "state_id"];
}
