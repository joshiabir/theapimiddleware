<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class logger extends Model
{
 
    protected $fillable =  [
        'key',
        'host',
        'domain_resolved',
        'client',
        'url',
        'request_headers',
        'request_body',
        'request_method',
        'response_satus',
        'response_headers',
        'response_body',
        'response_time',
        'analysis',
        'middleware_response',
        'prams'
    ];
}
