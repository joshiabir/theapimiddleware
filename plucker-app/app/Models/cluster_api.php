<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cluster_api extends Model
{
    use HasFactory;
    protected $fillable = ['api_id','cluster_id','status'];
}
