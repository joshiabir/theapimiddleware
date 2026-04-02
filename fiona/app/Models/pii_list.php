<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pii_list extends Model
{
    protected $fillable = ['regex','name','replacement'];
}
