<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class domain_routing extends Model
{
    protected $fillable = ['ip','host','status','policy','user_id','rate_limit','protocol','client_policy'];
}
