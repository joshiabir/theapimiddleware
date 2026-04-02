<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dlp_policy extends Model
{
    use HasFactory;

    protected $fillable = ['domain','cluster_policy_id','type','value'];
}
