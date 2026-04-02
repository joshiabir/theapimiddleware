<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class response_time extends Model
{
    protected $fillable = [
        'log_id',
        'host',
        'url',
        'response_time',
    ];
}
