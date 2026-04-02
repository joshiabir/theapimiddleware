<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class api_methods extends Model
{
    protected $fillable = ['host','url','method','count'];
}
