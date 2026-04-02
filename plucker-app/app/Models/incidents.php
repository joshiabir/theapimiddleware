<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class incidents extends Model
{
    protected $fillable = ['log_key','status','type','host'];
}
