<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cluster_policy_list extends Model
{
    use HasFactory;

    protected $fillable = ['policy_id', 'host', 'name', 'value'];
}
