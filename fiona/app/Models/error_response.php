<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class error_response extends Model
{
    protected $fillable = [
        'log_id',
        'host',
        'url',
        'response_status',      
    ];
}
