<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class app_user extends Model
{
    protected $fillable =[
            'client',
            'host',
            'count',
    ];
}
